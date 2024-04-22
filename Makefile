makemigrations:
	/Applications/XAMPP/bin/php -q ${PWD}/migrations/init_db.php

init_permissions:
	/Applications/XAMPP/bin/php -q ${PWD}/migrations/init_permissions.php
