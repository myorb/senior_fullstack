# Test task for senior fullstack developer

This project provides an API for hotels to get their reviews and their average score. It also provides a page where the hotel guests can submit reviews.

# Todo

The task includes two parts:

1. API (to get the average and reviews as implemented in ApiController)
2. Review form which is build in VueJs

## Todo part one:

- We need to improve the code quality by adopting the SOLID principles and/or other best practices.
- The Hotel can potentially have thousands of reviews, so keep that in mind for performance considerations.
- Currently, the average API is using hotelId, but Hotel entity should be identified by a UUID and have a relation to its Reviews.
- To keep this task simple we are not generating other hashes or access keys for using this widget but simply stick to the UUID.
- The response should be cached for clients for 1 hour.

## Todo part two:

- Within the existing project, please create a Vue app, using the provided screen-shot here as reference. Use webpack to build the app.
- Implement only two questions in the form: score (mandatory) and comment(optional).
- On Submit, show either "success" or "not valid" somewhere on the page (on your choice).
- Don't use Bootstrap.
- Use a CSS preprocessor.
- Preferably use a CSS methodology (such as BEM, etc.)
- Having automated tests is a plus.

## Requirement

- Refactor the current application, don't start something from scratch.
- All the code should be in the current application. For new additions you can implement new Controllers.
- Clean-code and SOLID principles are reviewed.
- Provide README file and provide the steps to execute the application.

# Deadline

Please complete the task in three days.

# Setup Maual

- composer install
- create schema
- load fixtures
- use the `symfony serve` or the builtin php server for development

# Setup Docker

You need install docker & docker-compose !

```bash
$make build up migrate
```

Then try http://localhost

For more information use

```bash
$make help
```

# Local development

```bash
yarn run watch
```
