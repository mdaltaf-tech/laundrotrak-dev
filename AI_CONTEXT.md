# Laundrotrak Architecture

Framework: Laravel 11

UI: Livewire

Database: MySQL

Coding Rules:

- No business logic in Livewire
- Actions modify data
- Services calculate
- Queries fetch
- Data objects carry typed data

Business Rules:

- Credit customers can receive delivery with pending balance.
- Standard customers cannot.
- Closing Cash is entered manually.
- Extra/Less Cash are calculated.

Naming:

- Order Value (not Sales)
- Cash Reconciliation (not Day Closing)

Laundrotrak Principles

- Don't make users calculate what the system already knows.
- Use business language, not technical language.
- Optimize for speed during daily operations.
- Every screen should answer one business question.
- Make common tasks effortless; keep advanced tasks available but unobtrusive.
