<?php

namespace App\Support;

final class LocalizedMessage
{
    private const PREFIX = '@i18n:';

    public static function store(string $key, array $parameters = []): string
    {
        return self::PREFIX.json_encode(['key' => $key, 'parameters' => $parameters], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
    }

    public static function label(string $value): array
    {
        return ['label' => $value];
    }

    public static function status(?string $value): string
    {
        $key = 'labels.'.$value;

        return trans()->has($key) ? __($key) : (string) $value;
    }

    public static function render(?string $value): ?string
    {
        if ($value === null || $value === '') {
            return $value;
        }

        if (str_starts_with($value, self::PREFIX)) {
            $message = json_decode(substr($value, strlen(self::PREFIX)), true);
            if (is_array($message) && is_string($message['key'] ?? null) && is_array($message['parameters'] ?? null)) {
                $parameters = array_map(
                    fn ($parameter) => is_array($parameter) && isset($parameter['label'])
                        ? self::status($parameter['label'])
                        : $parameter,
                    $message['parameters']
                );

                return __($message['key'], $parameters);
            }
        }

        // Keep old notifications readable without rewriting existing database rows.
        foreach (self::legacyPatterns() as [$pattern, $key, $labels]) {
            if (preg_match($pattern, $value, $matches)) {
                $parameters = array_filter($matches, fn ($key) => is_string($key), ARRAY_FILTER_USE_KEY);
                foreach ($labels as $name) {
                    $parameters[$name] = self::status($parameters[$name]);
                }

                return __($key, $parameters);
            }
        }

        return __($value);
    }

    private static function legacyPatterns(): array
    {
        static $patterns;
        if ($patterns !== null) {
            return $patterns;
        }

        $patterns = [];
        $templates = require resource_path('translations/legacy-messages.php');
        foreach ($templates as $key => $labels) {
            foreach (array_unique([$key, __($key, [], 'en'), __($key, [], 'fr')]) as $template) {
                if (! str_contains($template, ':value')) {
                    continue;
                }
                $pattern = preg_quote($template, '~');
                preg_match_all('/:(value[0-9]+)/', $template, $names);
                foreach ($names[1] as $name) {
                    $pattern = str_replace(preg_quote(':'.$name, '~'), '(?P<'.$name.'>.*?)', $pattern);
                }
                $patterns[] = ['~^'.$pattern.'$~usD', $key, $labels];
            }
        }

        return $patterns;
    }
}
