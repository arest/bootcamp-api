Viddyoze Bootcamp
========

A Symfony project created on September 18, 2017, 6:17 am.


QUESTIONS
========
	1) What will the API look like? Do you have time to make it look and smell RESTful or 
	will you have to settle for something less elegant?

	They are real RESTful and compatible with the rest client. I had only to add some headers userful for pagination.

	2) How will you handle auth? 

	The initial choice was to add api key. So there is a custom Service Provider looking for an apikey both on query string or as a custom header. The administrator can add users and change their api key.
	Yes I implemented OAuth2 on branch "oauth"

	3) Further to 2. How will your plugin work when its installed on multiple blogs? Is there a single shared pool of quotes or are they somehow divided?

	There is just a single pool of quotes. Another blog can have another access user.

	4) How much validation do you have time to do and where does it happen? Clearly in form validation in JS would be nice but what about when JS is turned off? What about malicious callers for the API? Do you have time to demonstrate a sensible scheme in all places?

	There are both frontend and backend validations. If JS is turned off, as my app in entirely based on Js/React it won't show. It is better to force https for every api call. Through CORS we can limit the hosts/blogs.


DELIVERABLES
========
	1) git@github.com:arest/bootcamp-api.git
	
	2) git@github.com:arest/Viddyoze_wordpress.git

	3) AdminBundle is a my recycled bundle to manage CRUD. /admin/dashboard (user:admin, pwd: admin)
	   Wordpress CRUD is a React app using components from Admin on Rest.
	   API documentation /api/doc/
	   Docker / Docker compose used to setup the aws instance ( nginx, php-fpm, mysql, phpmyadmin )
	   