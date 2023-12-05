## Problem:

In ecommerce, when a customer abandons his cart, the merchant wants to send some automated emails to push the customer to complete his purchase.

The email reminder should contain a link to complete the checkout, you may fake the checkout part and base it on a call to action click performed from the email.
When the checkout is done the cart is obviously removed - as it was converted to an order.

### Cart reminders rules

- First email should be sent after 6 hours.
- Second email should be sent after 12 hours.
- Third email should be sent after 24 hours.

- Four hours after the third email is sent, if the cart has not been converted to an order, it should be deleted.
- If the customer performs the checkout from a received reminder email, there is obviously no need to send the next ones.
  
## What should be done

- Email reminders feature
- Active monitoring of the feature using logs
    - Email reminders are sent
    - Recovered carts (converted to orders)
    - Cron job run
    - Errors
- Tests
  


# Solution :
a simple cart reminder app built with php and vuejs 
## Usage

To get started, make sure you have [Docker installed](https://docs.docker.com/docker-for-mac/install/) on your system, and then clone this repository.

Next Clone the current project

Next, navigate in your terminal to the directory you cloned this, and spin up the containers for the web server by running `docker compose up -d --build webapp`.
or use  the shortcut `composer docker-up` 

Bringing up the Docker Compose network with `site` instead of just using `up`, ensures that only our site's containers are brought up at the start, instead of all of the command containers as well. The following are built for our web server, with their exposed ports detailed:

- **nginx** - `:8080`
- **mysql** - `:3306`
- **php** - `:9000`
- **redis** - `:6379`
- **mailhog** - `:8025`
- **adminer** - `:8888`

create a .env file from the .env.example file. Once the .env.example file has been copied to .env, the system will be setup with the required default values to run the demo (database/cache/mail), these values be changed if you want to customise your setup.

then install the needed dependencies by running the following commands

- `docker compose run --rm composer install`

- `docker compose run --rm npm run build`

the run this fakers to create some products and a cart to test 
- `docker exec -it php sh -c "php wizard migrate"`
- `docker exec -it php sh -c "php wizard seed"`


to run commands inside the php container run the following command

- `docker exec -it php sh -c "php wizard your-command" `

### links :
to access the emails : http://localhost:8025/

to access the adminer : http://localhost:8888/

to access the app : http://localhost:8080/
## Permissions Issues
If you encounter any issues with filesystem permissions while visiting the application or running a container command, try completing the following steps:

- Bring any container(s) down with `docker compose down`
- Copy the `.env.example` file in the root of this repo to `.env`
- Modify the values in the `.env` file to match the user/group that the `src` directory is owned by on the host system
- Re-build the containers by running `docker compose build --no-cache`

Then, either bring back up the container network or re-run the command you were trying before, and see if that fixes it.

