## What is the TIoS IoT platform?

TIoS is an integrated IoT platform that allows both individuals and industries to effectively connect to their environment.  

TIoS offers an end-to-end solution. We have hardware and software to enable any device and connect it to the internet. However, any hardware is potentially compatible with TIoS. It just need to have an internet connection and send HTTPS requests to TIoS API or pre-configured MQTT topics to a secured broker.

This is the [TiOS Web App repo] [TIoS Web Application](https://github.com/luiscolmenares/TiOS-webapp).

For source code of Mobile App (React), reach out directly.

## TIoS Web App features:

- Username / Password authentication: directly handled by TIoS Platform
- Real time data feed from your enabled devices: TIoS’ components work together to transform real time data from enabled sensors to personalized experiences for users.
- Real time control over enabled devices: control your connected actuators devices anytime, anywhere, directly from your phone
- In-app configuration for triggered alerts: triggers are actions based on rules of enabled devices. The moment the criteria is met, the trigger is activated, without delays. Your TIoS account includes sending emails and push notifications (anywhere in the world) and text messages (USA only). Also, you can create triggers that actuate on enabled devices. For example, turn on or off the light with a presence detector or change the temperature of the air conditioning depending on the temperature of the place
- In-app scheduler: For repetitive activities (for example, turn off the lights every day at 10pm, or send a weekly email report) or single event creation
- User Profile: update your personal data
- Extra security layer  (4-digit passcode) for sensitive actuators devices: TIoS adds another layer of security for actuators devices that can be considered sensitive (door locks, water valves, etc)
- Monitor Multiple spaces: the office, server room, your restaurant, or even your children's room. You can create unlimited Spaces where you can group unlimited number of enabled sensors and actuators devices.
- High Performance: TIoS is lightweight as most operations are handled over the cloud.
- In-app Support: Have a problem? You can contact us directly from the app.
- Security: TIoS takes priority on security. All transactions are encrypted and protected using SSL and authorization protocols.
- Much more to come!

## Installation Steps:
Clone the project to your local
Go to project root folder
Run “composer install”
Copy db backup to your local mysql

After bringing all dependencies, rename .env.example to .env, and update database credentials.

NOTE: You might need to update permissions in the key files:
chmod 600 storage/oauth-public.key
chmod 600 storage/oauth-private.key

Run local server in port 5000

php artisan serve --port=5000

API should be available at http://localhost:5000
API documentation should be available at http://localhost:5000/api/documentation
API postman file: https://drive.google.com/open?id=1WrVT85ksze6q9qfIF2l9PBTBnz9z-7UB
