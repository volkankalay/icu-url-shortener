# Eloquent model PHPDoc

- Document persisted model attributes with class-level `@property` tags. Use the runtime PHP type after casting and include `null` for nullable columns. Include timestamps and soft-delete columns where present in the schema.
- Document relationship properties, read-only accessors, and computed attributes used by the application with `@property-read`. Use `Collection<int, RelatedModel>` for to-many relationships, mark optional aggregate attributes nullable, and type relationship methods with their Eloquent generics.
- Update PHPDoc when a migration, cast, accessor, or relationship changes. Keep the documentation consistent with the implemented model and schema.
