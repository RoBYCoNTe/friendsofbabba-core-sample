# README 😵

This DEMO project was built on top of friendsofbabba-dockerfiles and using
friendsofbabba-core plugin for CakePHP.

## First steps

Run the project:

```sh
cd dockerfiles
sh start.sh
```

Install database:

```sh
docker exec -it friendsofbabba_core_sample_php bash
bin/cake migrations migrate
```

🎁 Enjoy!

## What this APP can do?

This app contains few examples:

- blog

### Blog

What I've done to create this blog:

- Created database tables (`blog_categories` and `blog_posts`).
- Using FriendsOfBabba/Core bake I've created entities, tables and workflows:

  ```sh
  bin/cake bake entity create BlogCategories
  bin/cake bake api create BlogCategories
  bin/cake bake workflow create BlogPosts -s Draft,Published -t Draft:Published,Published:Draft
  ```

Done! After I've customized Grids and Forms using CRUD engine provided by FriendsOfBabba/Core.
You can see it at work opening `src/Model/Table/BlogPostsTables.php` or `src/Model/Table/BlogCategoriesTable.php`

To use the UX associated to this example you can download and run [React-Admin Library](https://github.com/RoBYCoNTe/friendsofbabba-ra):

```sh
npm run i-all
npm run dev
```

🎁 Enjoy!
