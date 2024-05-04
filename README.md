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
