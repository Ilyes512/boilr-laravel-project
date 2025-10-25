# {{ProjectName}}

{{ProjectDescription}}

## Local setup

### Requirements

- [Docker](https://www.docker.com/products/docker-desktop) (macOS users should prefer
  [OrbStack](https://orbstack.dev/download) for better DX)
- [Task](https://taskfile.dev/installation/)

> [!NOTE]
> With the above requirements installed you should be able to start your local development environment pretty easily on
> Linux and MacOS. Windows users can follow the instructions in
> [Windows Local Development Setup](docs/windows-local-development-setup.md).

### Task

[Task](https://taskfile.dev/) is a task runner / build tool. It is a simple tool that allows you to define and run tasks
for your projects. It is similar to `make` but cross-platform and written in Go. To retrieve a complete list of all
tasks run `task --list` in your terminal. Here is a short list of tasks to get you started:

#### Starting the project

To get the project up and running, run `task up`. This will automate all the steps needed to get the project up and
running.

| Description      | Uri or host:port        | OrbStack domain            |
|------------------|-------------------------|----------------------------|
| Application url  | <http://localhost:8080> | <https://{{kebabcase ProjectShortName}}.local>      |
| Mailpit url      | <http://localhost:8025> | <https://mail.{{kebabcase ProjectShortName}}.local> |
| MariDB host port | `localhost:3306`        | `db.{{kebabcase ProjectShortName}}.local`          |
| Redis host port  | `localhost:6379`        | `redis.{{kebabcase ProjectShortName}}.local`       |

> [!TIP]
> If you are a macOS user and you are using OrbStack, you can navigate to <http://orb.local> to see the domains of all
> your running containers.

#### Stopping the project

To stop the project, run `task stop`. This won't delete the projects containers and volumes, so you can start the
project again with the same state later.

#### Stopping and cleaning up the containers and volumes

To stop and remove the project's containers and volumes, run `task down`. You database state will be lost.

#### Resetting the project

To reset the project, run `task reset`. This will stop the project and remove all containers and volumes. And finally
start the project up again.

#### Refreshing the application

To refresh the project when for instance you are switching to a different branch, run `task app:refresh`. This will for
instance (re)fetch the composer and npm dependencies, run the migrations and rebuild the front-end assets

#### Using Clockwork to debug the application

Checkout <https://{{kebabcase ProjectShortName}}.local/clockwork> to see the [clockwork](https://underground.works/clockwork/) dashboard.
