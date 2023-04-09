# Chat App

This is a simple chat application built with PHP and the Slim microframework. The application allows users to engage in chat conversations with other users and view their messages.

## Installation

To install the application, follow these steps:

1. Clone the Git repository to your local machine:

```bash
git clone https://github.com/yz-baskoy/my-chat-app
```
2. Install the dependencies using Composer:
```bash
cd chat-app
composer install
```
3. Create a new SQLite database file:
```bash
touch chat.db
```
4. Initialize the database schema using the included SQL script:
```bash
sqlite3 chat.db < schema.sql
```
5. Start the development server:
```bash
php -S localhost:8000 -t public
```
6. Visit http://localhost:8000 in your web browser to view the application.

## Usage
* POST /messages: Send a new message.
    * author_id: ID of the user sending the message
    * recipient_id: ID of the user receiving the message
    * content: The content of the message
* GET /messages/{recipient_id}: List all messages sent to a specific user.
* GET /new-messages: returns JSON data containing all messages that were sent after the specified timestamp.
* GET /users: List all users in the chat.
* POST /users/register: registers a user with the provided name, email, and password.
    * name
    * email
    * password

## Author
Yusuf Başköy

