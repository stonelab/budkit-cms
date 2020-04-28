
# budkit/cms

A content management system built on the [budkit/framework](http://github.com/stonelab/budkit-framework). 

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. 

### Prerequisites

| Software  | Minimum  |  Recommended | URI  | Extensions  |
|---|---|---|---|---|
|  Apache |  2.4+ | 2.4.3  | https://apache.org  |  mod_rewrite = on |
| PHP  |  7.4+ | 7.4.3  | https://php.net  | Magic Quotes GPC, MB String Overload = off<br >Zlib Compression Support, XML Support, INI Parser Support, JSON Support, MB Language = Default  |
| MariaDB  | 10.4+  | 10.4.12  | https://mariadb.org  |  InnoDB support required |


### Installing

For a complete experience, the recommended way to install the cms is as part of [budkit/server](https://github.com/stonelab/budkit-server) which adds all the required depedencies ([budkit/framework](https://github.com/stonelab/budkit-server), [budkit/repository](https://github.com/stonelab/budkit-repository) and [budkit/api](https://github.com/stonelab/budkit-api)).  

Alternatively, install via composer

```
composer require budkit/cms
```

After installation use the setup wizard at ```/admin/setup/install``` 


## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/stonelab/budkit-cms/tags). 

## Authors

* **Livingstone Fultang** - *Initial work* 

See also the list of [contributors](https://github.com/stonelab/budkit-cms/graphs/contributors) who participated in this project.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details


