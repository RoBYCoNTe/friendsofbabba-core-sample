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
