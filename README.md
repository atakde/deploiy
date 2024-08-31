### Deploy yaml poC

```yaml
deploy:
  appName: "App Name"
  version: "1.0.0"
  environment:
    type: "production"
    host: "your_production_server"
    port: 80
    deployPath: "/var/www/your_path/"
  preDeploy:
      enableRevisionReplace: true
      revisionPlaceholder: "{REV}"
      revisionReplecableExtensions: ["js", "html"]
      revisionReplaceSkipPaths: ["node_modules", "vendor"]
    commands:
      - name: "whoami"
        command: "whoami"
      - name: "Pull code"
        command: "sudo git pull 2>&1"
      - name: "Install dependencies"
        command: "composer install 2>&1"
      - name: "Install npm dependencies"
        command: "pnpm install --force 2>&1"
      - name: "Build assets"
        command: "pnpm gulp 2>&1"
  postDeploy:
    commands:
      - name: "say finished"
        command: "echo 'finished'"

```
### Example of deploy.php

```php
<?php

try {
  require_once '/any_folder/deploiy/vendor/autoload.php';

  $yamlParser = new \Atakde\Deploiy\Parser\YamlParser();
  $runner = new \Atakde\Deploiy\Runner\Runner();
  $deployer = new \Atakde\Deploiy\Deployer($yamlParser, $runner);

  $deployer->deployWithConfigPath('/any_folder/any_project/deploy.yaml');

} catch (\Throwable $th) {
}
```

### Nginx conf example if needed
```
location /deploy-webhook {
  try_files $uri $uri/ /deploy.php?$query_string;
}
```
