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
  hostname = "working_weather_app.dev"
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
}
