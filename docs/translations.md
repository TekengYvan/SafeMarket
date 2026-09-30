# English and French translations

The selected language is stored in the web session and applied by SetLocale.
API clients can send Accept-Language: en or Accept-Language: fr.

- Page text and immediate feedback: use __('Source text', $parameters) and add matching entries to lang/en.json and lang/fr.json.
- Stored system messages: use LocalizedMessage::store('events.key', $parameters) with entries in both lang/*/events.php. Notification title/content and transaction description accessors translate when read, so the sender or webhook language does not determine the recipient language.
- Status parameters inside stored messages: use LocalizedMessage::label($status). Display status codes with LocalizedMessage::status($status); keep stored codes unchanged.
- User-written names, products, reviews, and chat text remain unchanged. Built-in category names have dictionary translations.
- Existing plain-text system messages are supported by resources/translations/legacy-messages.php. Unknown historical text remains readable.
- JavaScript translations must use Js::from() or escaped HTML data attributes, never interpolation inside JavaScript string quotes.

Run php artisan migrate when deploying: the translation migration expands transaction descriptions to text so long product names and message parameters fit. It does not rewrite existing transactions.

Verify with php artisan test --compact tests/Feature/LocalizationTest.php.
