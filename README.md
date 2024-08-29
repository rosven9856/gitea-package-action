<p align="center">
    <img width="560" height="260" src="docs/image/github_gitea_actions.jpg" alt="github gitea actions">
</p>

# Updating a package in the Gitea system using GitHub Actions

[![License](https://img.shields.io/github/license/rosven9856/gitea-package-action)](https://github.com/rosven9856/gitea-package-action/blob/master/LICENSE)

This action will update the package version in the Gitea system using the API and output debugging information to the log.


## Example usage

```yaml
    steps:
      - uses: rosven9856/gitea-package-action@0.1.0
        with:
          gitea_instance_base_url: "https://gitea_instance_url"
          gitea_access_token: "${{ secrets._GITEA_ACCESS_TOKEN }}"
          gitea_owner: "owner"
          gitea_repository: "repository"
          gitea_package_registry: "composer"
```


## Developing

build
```shell
docker build . --build-arg=PHP_VERSION=8.3 -t=gitea-package-action
```

initialization
```shell
docker run --rm -e GITHUB_WORKSPACE=/usr/bin/app -v .:/usr/bin/app gitea-package-action composer install
```

running
```shell
docker run --rm -e GITHUB_WORKSPACE=/usr/bin/app -v .:/usr/bin/app gitea-package-action php app.php
```
