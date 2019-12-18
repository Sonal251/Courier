<?php

if (!function_exists('createForeignKey')) {
    function createForeignKey(
        \Illuminate\Database\Schema\Blueprint $table,
        string $columnName,
        bool $nullable = false,
        string $foreignColumnName = null,
        string $foreignTableName = null
    ): \Illuminate\Support\Fluent
    {
        if (\is_null($foreignTableName)) {
            \preg_match('/^(.*)_id$/', $columnName, $matches);
            $foreignTableName = Str::plural($matches[1]);
        }
        if (\is_null($foreignColumnName)) {
            \preg_match('/^.*_([^_]+)$/', $columnName, $matches);
            $foreignColumnName = $matches[1];
        }
        $column = $table->unsignedInteger($columnName);
        if ($nullable) {
            $column->nullable();
        }

        return $table->foreign($columnName)
            ->references($foreignColumnName)
            ->on($foreignTableName);
    }
}

if (!function_exists('createForeignKey')) {
    function jsonResponse($data)
    {
        return response()->json([
            'status' => 'success',
            'data'   => $data,
        ]);
    }
}

if (!function_exists('jsonResponse')) {
    function jsonResponse($data = null, $status = 200, array $headers = [], $options = 0)
    {
        return response()->json([
            'status' => $status < 400 ? 'success' : 'error',
            'data'   => $data,
        ], $status, $headers, $options);
    }
}
