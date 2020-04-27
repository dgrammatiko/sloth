## Welcome to this step by step guide creating an SPA Joomla 4 Template

### Each step is a branch of this repo

# Step one, Setting up the stage

- Clone the repo

```bash
git clone git@github.com:dgrammatiko/sloth.git
```

- Install dependancies

```bash
npm install
```

- Initial the workspace


```bash
npm run init
```

- Serve the _www folder: This depends on your system and your LAMP setup, you could use a docker image (PHP, MYSQL, Apache) and point to the _www folder. For MAMP, WAMP etc ypu could either point the serving directory to the _www of this repo or symlink it

- Install Joomla 4

- Discover the template named `sloth` and install it

- Make the sloth template the default

Congrats, now you have a way to develop the template with modern tools included. At this point to template is just some hardcoded static html. Let's convert this to a dynamic data driven Joomla template, on step-two...


