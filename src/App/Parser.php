<?php

namespace App;

use Exception;

class Parser
{
    public function __construct(private readonly array $schema)
    {
    }

    public function parse(string $args): ParserResult
    {
        // $values = preg_split("/\s+/", $args);
        // Splits on white space, unless white space is between " " or ' '
        $values = preg_split('/\s+(?=(?:[^\'"`]*([\'"`])[^\'"`]*\1)*[^\'"`]*$)/', $args);

        $result = [];

        for ($i = 0; $i < count($values); $i++) {
            $val = $values[$i];

            if ($this->isValidKey($val)) {
                $key = substr($val, 1);

                if (!isset($key)) {
                    continue;
                }

                // If no value or value is key -> null
                $val = isset($values[$i + 1])
                    ? (!$this->isValidKey($values[$i + 1]) ? $values[$i + 1] : null)
                    : null;

                $type = $this->schema[$key]['type'];

                $val = match($type) {
                    'bool' => $this->parseBool($key),
                    'int' => $this->parseInt($key, $val),
                    'string' => $this->parseString($key, $val),
                };

                $result[$key] = $val;
                // We skip the next value because we already parsed it
                // for the current key
                // Unless flag is a boolean (boolean flags don't have values)
                if (!$type === 'bool') {
                    $i++;
                }
            }
        }

        return new ParserResult($result, $this->getDefaults());
    }

    private function getDefaults(): array
    {
        $result = [];

        foreach ($this->schema as $key => $value) {
            if ($value['type'] === 'bool') {
                $result[$key] = $value['default'] ?? false;
                continue;
            }

            if (isset($value['default'])) {
                $result[$key] = $value['default'];
            }
        }

        return $result;
    }

    private function isValidKey(string $input): bool
    {
        return str_starts_with($input, "-") && isset($this->schema[substr($input, 1)]);
    }

    private function parseBool(string $key): bool
    {
        return true;
    }

    private function parseInt(string $key, ?string $val): int
    {
        if ($val === null) {
            throw new Exception("INT_KEY_WITH_NO_VALUE");
        }

        if (!is_numeric($val)) {
            throw new Exception("NON_NUMERIC_VALUE_FOR_INT_KEY");
        }

        return intval($val);
    }

    private function parseString(string $key, ?string $val): string
    {
        if ($val === null || strlen($val) === 0) {
            throw new Exception("STRING_KEY_WITH_NO_VALUE");
        }

        return trim($val, '"\'');
    }
}