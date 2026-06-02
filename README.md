Setup:
 - Ensure you have the `pdo_sqlite` and `sqlite3` extensions enabled in your PHP ini file
 - Navigate to the folder containing this readme
 - Run `sqlite3.exe database.sqlite3` to create the database
 - Inside the sqlite console, run `.read setup.sql` to create the urls table
 - Exit the sqlite console and run `php.exe -S localhost:8000 -t public` to start the server
 - Navigate to `http://localhost:8000` in your browser