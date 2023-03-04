## About the project
It's a project to manage the GTFS package. It's a Laravel project. With an Mobile app and a Web app (for admin).
- React Native for the mobile app : https://github.com/HoctoPrism/AlmostHereMobile
- NextJS for the web app : https://github.com/HoctoPrism/AlmostHereWeb

## GTFS commands

This project includes multiples commands to manage the GTFS package. The GTFS part of the project works around a main GTFS archive called gtfs-smtc.zip.

You can use the command : **`php artisan list gtfs`** to get the list of commands

Here's a list of commands available :
- Check if the main archive does exist
- Check the hash of the main archive and the one of the api, it used to know if an update is needed or not.
- Get the list of all backups available on the machine
- Allows to download an update from API and uses it as the main archive. Create a backup of the actual main archive before update.
- Allows to replace the main archive using a backup archive
- Allows to delete one or multiple backups archive
- Extracts the main archive content
