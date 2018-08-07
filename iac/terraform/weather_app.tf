provider "docker" {
  host = "tcp://var/run/docker.sock"
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
  networks = ["${docker_network.private_network.id}"]
  command = ["supervisord", "-n"]
}
