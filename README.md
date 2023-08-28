# Blogit
![Blogit Banner](repository-banner.png)

Welcome to **Blogit**, a blogging platform built using Vanilla PHP and AJAX. With Blogit, you can create, manage, and share your thoughts and ideas through blog posts, while also engaging with your readers through comments.

## Features
- **Search and Pagination**: Easily search for specific blog posts and navigate through them using the built-in search and pagination features.

- **Authentication and User Management**: Securely manage your blogging experience with user authentication, allowing you to log in and create an account to start sharing your stories.

- **Admin Panel**: Blogit offers a comprehensive admin panel where you can manage your posts, edit existing ones, and even add new posts without hassle.

- **Comment Section**: Engage with your readers through the comment section available on each blog post. Foster discussions and receive feedback from your audience.

## Installation
### Prerequisites
Before you begin, ensure you have the following prerequisites installed:

- Docker: [Installation Guide](https://docs.docker.com/get-docker/)

- Docker Compose: [Installation Guide](https://docs.docker.com/compose/install/)

### Getting Started
1. Clone this repository:
```shell
git clone https://github.com/higorcard/blogit.git

cd blogit
```

2. Edit the .env file with your desired configuration.

3. Build and start the Docker containers:
```shell
sudo docker-compose up -d
```

4. Install Composer (if not installed):
  - If Composer isn't already installed, get it from [Installation Guide](https://getcomposer.org/download/).

5. Run Composer
  - In your terminal, navigate to your project directory and run:
  ```shell
  composer install
  ```

3. Set up your database credentials in **classes/Connection.php**.

5. Access http://localhost:8080 in your web browser.
  - Username: **root**
  - Password: (use the password defined in your **.env file**)

5. Once logged in, import the **'blogit.sql'** file through the phpMyAdmin panel.

5. Launch the application using a local development server.

## Usage
1. Register for an account or log in if you already have one.

2. Explore the blog posts on the homepage.

3. Click on a post to view its full content.

4. Leave comments on posts to engage with other readers.

5. If you're an admin, log in and access the admin panel to manage posts, create new ones, and edit existing content.

## Contributing
We welcome contributions to enhance and expand the capabilities of Blogit.

## License
This project is licensed under the MIT License.

---

Thanks for using Blogit!

<img src="repository-logo.png" alt="Blogit Logo" width="100" height="100">
