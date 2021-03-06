provider "docker" {
  host = "tcp://127.0.0.1:2375"
}

resource "docker_network" "private_network" {
  name = "weather_app_net"
}

# Create a container
resource "docker_container" "weather_app" {
  image = "private/weather_app:latest"
  name  = "working_weather_app"
  ports {
    internal = 80
    external = 80
  }
  volumes {
    host_path = "/var/weather_app"
    container_path = "/var/weather_app"
  }

  networks = ["${docker_network.private_network.id}"]
  command = ["supervisord", "-n"]

  provisioner "local-exec" {
    command = "docker exec -t working_weather_app /var/weather_app/bin/composer.phar --working-dir=/var/weather_app install"
  }
}

resource "docker_container" "weather_app_database" {
  image = "private/database:latest"
  name  = "weather_app_database"
  ports {
    internal = 3306
    external = 3306
  }
  networks = ["${docker_network.private_network.id}"]
  command = ["mysqld_safe"]
}
