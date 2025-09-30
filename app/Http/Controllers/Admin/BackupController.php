<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BackupController extends Controller
{
    /**
     * Display the backup management page.
     */
    public function index()
    {
        $backups = $this->getBackupFiles();
        
        return view('admin.backup.index', compact('backups'));
    }

    /**
     * Create a new database backup.
     */
    public function create(Request $request)
    {
        try {
            $filename = 'backup_' . Carbon::now()->format('Y-m-d_H-i-s') . '.sql';
            $backupPath = storage_path('app/backups/' . $filename);
            
            // Ensure backup directory exists
            if (!file_exists(storage_path('app/backups'))) {
                mkdir(storage_path('app/backups'), 0755, true);
            }
            
            // Get database configuration
            $database = config('database.connections.mysql.database');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');
            $host = config('database.connections.mysql.host');
            $port = config('database.connections.mysql.port');
            
            // Find mysqldump executable
            $mysqldumpPath = $this->findMysqldumpPath();
            if (!$mysqldumpPath) {
                // Fallback to Laravel's database backup method
                return $this->createLaravelBackup($filename);
            }
            
            // Create mysqldump command with full path
            $command = sprintf(
                '%s --host=%s --port=%s --user=%s --password=%s --single-transaction --routines --triggers %s > %s 2>&1',
                escapeshellarg($mysqldumpPath),
                escapeshellarg($host),
                escapeshellarg($port),
                escapeshellarg($username),
                escapeshellarg($password),
                escapeshellarg($database),
                escapeshellarg($backupPath)
            );
            
            // Execute the backup command
            $output = [];
            $returnCode = 0;
            exec($command, $output, $returnCode);
            
            if ($returnCode === 0 && file_exists($backupPath) && filesize($backupPath) > 0) {
                return redirect()->route('admin.backup.index')
                    ->with('success', 'Database backup created successfully: ' . $filename);
            } else {
                $errorOutput = implode("\n", $output);
                throw new \Exception('Backup command failed with return code: ' . $returnCode . '. Output: ' . $errorOutput);
            }
            
        } catch (\Exception $e) {
            return redirect()->route('admin.backup.index')
                ->with('error', 'Failed to create backup: ' . $e->getMessage());
        }
    }

    /**
     * Download a backup file.
     */
    public function download($filename)
    {
        $filePath = storage_path('app/backups/' . $filename);
        
        if (!file_exists($filePath)) {
            return redirect()->route('admin.backup.index')
                ->with('error', 'Backup file not found.');
        }
        
        return response()->download($filePath);
    }

    /**
     * Restore database from backup.
     */
    public function restore(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|mimes:sql|max:102400' // Max 100MB
        ]);
        
        try {
            $backupFile = $request->file('backup_file');
            $tempPath = $backupFile->storeAs('temp', 'restore_' . time() . '.sql');
            $fullPath = storage_path('app/' . $tempPath);
            
            // Get database configuration
            $database = config('database.connections.mysql.database');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');
            $host = config('database.connections.mysql.host');
            $port = config('database.connections.mysql.port');
            
            // Find mysql executable
            $mysqlPath = $this->findMysqlPath();
            if (!$mysqlPath) {
                throw new \Exception('mysql command not found. Please ensure MySQL client tools are installed.');
            }
            
            // Create mysql command to restore
            $command = sprintf(
                '%s --host=%s --port=%s --user=%s --password=%s %s < %s 2>&1',
                escapeshellarg($mysqlPath),
                escapeshellarg($host),
                escapeshellarg($port),
                escapeshellarg($username),
                escapeshellarg($password),
                escapeshellarg($database),
                escapeshellarg($fullPath)
            );
            
            // Execute the restore command
            $output = [];
            $returnCode = 0;
            exec($command, $output, $returnCode);
            
            // Clean up temp file
            unlink($fullPath);
            
            if ($returnCode === 0) {
                return redirect()->route('admin.backup.index')
                    ->with('success', 'Database restored successfully from backup.');
            } else {
                $errorOutput = implode("\n", $output);
                throw new \Exception('Restore command failed with return code: ' . $returnCode . '. Output: ' . $errorOutput);
            }
            
        } catch (\Exception $e) {
            return redirect()->route('admin.backup.index')
                ->with('error', 'Failed to restore backup: ' . $e->getMessage());
        }
    }

    /**
     * Delete a backup file.
     */
    public function delete($filename)
    {
        try {
            $filePath = storage_path('app/backups/' . $filename);
            
            if (file_exists($filePath)) {
                unlink($filePath);
                return redirect()->route('admin.backup.index')
                    ->with('success', 'Backup file deleted successfully.');
            } else {
                return redirect()->route('admin.backup.index')
                    ->with('error', 'Backup file not found.');
            }
            
        } catch (\Exception $e) {
            return redirect()->route('admin.backup.index')
                ->with('error', 'Failed to delete backup: ' . $e->getMessage());
        }
    }

    /**
     * Get list of backup files.
     */
    private function getBackupFiles()
    {
        $backupDir = storage_path('app/backups');
        $files = [];
        
        if (is_dir($backupDir)) {
            $fileList = scandir($backupDir);
            
            foreach ($fileList as $file) {
                if ($file !== '.' && $file !== '..' && pathinfo($file, PATHINFO_EXTENSION) === 'sql') {
                    $filePath = $backupDir . '/' . $file;
                    $files[] = [
                        'filename' => $file,
                        'size' => filesize($filePath),
                        'created_at' => filemtime($filePath),
                        'formatted_size' => $this->formatBytes(filesize($filePath)),
                        'formatted_date' => Carbon::createFromTimestamp(filemtime($filePath))->format('M d, Y H:i:s')
                    ];
                }
            }
            
            // Sort by creation time (newest first)
            usort($files, function($a, $b) {
                return $b['created_at'] - $a['created_at'];
            });
        }
        
        return $files;
    }

    /**
     * Format bytes to human readable format.
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }

    /**
     * Find the mysqldump executable path.
     */
    private function findMysqldumpPath()
    {
        $possiblePaths = [
            '/opt/homebrew/bin/mysqldump',  // macOS Homebrew
            '/usr/local/bin/mysqldump',     // macOS/Linux
            '/usr/bin/mysqldump',           // Linux
            '/bin/mysqldump',               // Linux
            'mysqldump'                     // Fallback to PATH
        ];

        foreach ($possiblePaths as $path) {
            if ($path === 'mysqldump') {
                // Check if mysqldump is in PATH
                $output = [];
                $returnCode = 0;
                exec('which mysqldump 2>/dev/null', $output, $returnCode);
                if ($returnCode === 0 && !empty($output[0])) {
                    return $output[0];
                }
            } elseif (file_exists($path) && is_executable($path)) {
                return $path;
            }
        }

        return null;
    }

    /**
     * Find the mysql executable path.
     */
    private function findMysqlPath()
    {
        $possiblePaths = [
            '/opt/homebrew/bin/mysql',  // macOS Homebrew
            '/usr/local/bin/mysql',     // macOS/Linux
            '/usr/bin/mysql',           // Linux
            '/bin/mysql',               // Linux
            'mysql'                     // Fallback to PATH
        ];

        foreach ($possiblePaths as $path) {
            if ($path === 'mysql') {
                // Check if mysql is in PATH
                $output = [];
                $returnCode = 0;
                exec('which mysql 2>/dev/null', $output, $returnCode);
                if ($returnCode === 0 && !empty($output[0])) {
                    return $output[0];
                }
            } elseif (file_exists($path) && is_executable($path)) {
                return $path;
            }
        }

        return null;
    }

    /**
     * Create backup using Laravel's database methods (fallback).
     */
    private function createLaravelBackup($filename)
    {
        try {
            $backupPath = storage_path('app/backups/' . $filename);
            
            // Get all table names
            $tables = DB::select('SHOW TABLES');
            $databaseName = config('database.connections.mysql.database');
            $tableKey = 'Tables_in_' . $databaseName;
            
            $sql = "-- Database Backup Created: " . Carbon::now()->format('Y-m-d H:i:s') . "\n";
            $sql .= "-- Database: " . $databaseName . "\n\n";
            $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";
            
            foreach ($tables as $table) {
                $tableName = $table->$tableKey;
                
                // Get table structure
                $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
                $sql .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
                $sql .= $createTable[0]->{'Create Table'} . ";\n\n";
                
                // Get table data
                $rows = DB::table($tableName)->get();
                if ($rows->count() > 0) {
                    $sql .= "INSERT INTO `{$tableName}` VALUES\n";
                    $values = [];
                    foreach ($rows as $row) {
                        $rowValues = [];
                        foreach ((array)$row as $value) {
                            if ($value === null) {
                                $rowValues[] = 'NULL';
                            } else {
                                $rowValues[] = "'" . addslashes($value) . "'";
                            }
                        }
                        $values[] = "(" . implode(',', $rowValues) . ")";
                    }
                    $sql .= implode(",\n", $values) . ";\n\n";
                }
            }
            
            $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";
            
            // Write to file
            file_put_contents($backupPath, $sql);
            
            return redirect()->route('admin.backup.index')
                ->with('success', 'Database backup created successfully using Laravel method: ' . $filename);
                
        } catch (\Exception $e) {
            return redirect()->route('admin.backup.index')
                ->with('error', 'Failed to create Laravel backup: ' . $e->getMessage());
        }
    }
}
