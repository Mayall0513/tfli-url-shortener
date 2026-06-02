Structure:

- MVC framework. Each layer in the framework has specific responsibilities:
	- Controllers handle requests, perform input filtering and select the appropriate response
	- Models abstract database access from controllers and enforce business rules
	- Views contain page markup
- A simple router determines where to enter the MVC stack.
- Controllers opt into capabilities by implementing HTTP method interfaces. This allows for modular controllers while also ensuring a consistent interface.

Behaviour decisions:

- Submitting an existing URL with a different expiry date updates the existing URL rather than displaying an error. This provides for the simplest user experience.
- A URL length limit of 4096 characters is enforced to limit resource usage while accommodating for most URLs.
- URLs must include a valid scheme (http or https)

Changes if more time was allocated:

- Use factories to inject dependencies into controllers. This would reduce controller coupling, simplify swapping underlying implementations (for example, from sqlite3 to MySQL) and make unit tests easier to implement in future.
- Use an encoding scheme other than base64 for the shortened URL, base64 is fine for the needs of the project and is inbuilt into PHP but lengthens the shortened URL beyond what would be ideal. Using base62 would yield shorter URLs but would need to be researched and implemented taking additional time.
- Implement both client side and server side validation. Given the time constraint, I prioritised server side validation as it's reliable and protected from tampering. Client validation as well as server would reduce server load and improve user experience, especially when network latency is high.

Changes if more technologies were allowed:

- I did not think rate limiting was worth attempting to implement in the allotted time and technologies. In a production environment, I would implement rate limiting using Redis to protect server resources and achieve a balance of performance and reliability.
- Switch from using the development PHP server and custom router to a dedicated HTTP server and router such as Apache or Nginx.
- If there was a cron tab or another script scheduler was available, I would add a cleanup script to remove URLs that have expired from the database.

Security considerations:

- Prepared statements prevent SQL injection.
- CSRF risk is low because the project does not maintain authenticated sessions. If authentication was added, CSRF mitigation would become necessary.
- XSS risk is minimised because only server-generated or error messages are inserted into the client's DOM (shortened URLs created in `URLShortenerController.php` and error messages from JavaScript), I also use `textContent` rather than `innerHTML` to set content as an extra layer of mitigation.

Reliability considerations:

- The primary reliability concern during design/development was avoiding race conditions when shortening and inserting URLs. The first part of mitigation was to simplify shortened URL generation. Rather than randomly, shortened URLs are generated deterministically based off the URL's database id. This allows leveraging the database's ordered writes to remove the need for collision checks and limit the risk of race conditions.