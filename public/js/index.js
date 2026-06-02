document.addEventListener("DOMContentLoaded", function() {
    // We need to know the user's timezone for the client's local datetime to be meaningful to the server
    document.getElementById('timezone').value = Intl.DateTimeFormat().resolvedOptions().timeZone;

    const submitButton = document.getElementById("submit_button");
    if (submitButton) {
        submitButton.addEventListener("click", async function() {
            const form = document.getElementById("form");
            const formData = new FormData(form);

            try {
                const submitResponse = await fetch(
                    "/api/v1/url_shortener",
                    {
                        method: "POST",
                        body: new URLSearchParams(formData),
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        }
                    }
                );

                const { ok, error, shortened_url: shortenedUrl } = await submitResponse.json();
                const responseHolder = document.getElementById("response_holder");

                if (ok) {
                    const urlAnchor = document.createElement("a");

                    urlAnchor.setAttribute("href", shortenedUrl);
                    urlAnchor.setAttribute("target", "_blank");
                    urlAnchor.textContent = shortenedUrl;
    
                    responseHolder.replaceChildren(urlAnchor);
                }
                else {
                    const errorHolder = document.createElement("span");

                    switch (error) {
                        case 'MISSING_URL':
                            errorHolder.textContent = "No URL provided!";
                        break;
                        case 'INVALID_URL_PROVIDED':
                            errorHolder.textContent = "Invalid url provided!";
                        break;
                        case 'INVALID_URL_TOO_LONG':
                            errorHolder.textContent = "The provided url is too long! (max. 4096 characters)";
                        break;
                        case 'INVALID_EXPIRY_PROVIDED':
                            errorHolder.textContent = "Invalid expiry provided!";
                        break;
                        case 'INVALID_EXPIRY_IN_PAST':
                            errorHolder.textContent = "The expiry must be in the future!";
                        break;
                        default:
                            errorHolder.textContent = "An unknown error occurred!";
                        break;
                    }
    
                    responseHolder.replaceChildren(errorHolder);
                }
            }
            catch (error) {
                const errorHolder = document.createElement("span");
                errorHolder.textContent = "An unknown error occurred!";
                responseHolder.replaceChildren(errorHolder);
            }
        });
    }
});