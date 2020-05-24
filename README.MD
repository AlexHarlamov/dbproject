# AXIOM PROJECT
## Installation guide
### Workspace requires
#### Virtual Environment
- Virtualbox (www.virtualbox.org)
- Vagrant (www.vagrantup.com) <br>
    plugins: <br>
        vagrant-hostsupdater,<br>
        vagrant-vbguest
#### Virtual workspace
- ubuntu/bionic64 vagrant box (Ubuntu 18.04) (https://app.vagrantup.com/ubuntu/boxes/bionic64)
- Mysql (https://www.mysql.com/)
- Nginx (https://www.nginx.com/)
- php7.4 (php7.4-fpm) (https://gtxtymt.xyz/blog/ubuntu-install-php74-fpm)
#### Helpful things (developing)
- Adminer (https://www.adminer.org/)
### Local environment installation guide
1. Install Virtualbox
2. Install Vagrant
3. Configure VagarantFile : <br>
    create file
    ```shell script
    touch Vagrantfile
    ```
    and insert code there 
    ```ruby
    Vagrant.configure("2") do |config|
    
      config.vm.box = "ubuntu/bionic64"
      config.ssh.username = "vagrant"
     
      config.vm.hostname = "axiom.dev" # Setting up hostname
      config.hostsupdater.aliases = ["axiom.dev"]
      
      config.vm.network "private_network", ip: "192.168.205.31" # Setting up machine's IP Address
      config.vm.provision :shell, path: "bootstrap.sh" # Provisioning with shell script
    
       config.vm.provider :virtualbox do |vb|
         vb.memory = 2048
         vb.cpus = 2
       end
    
    end
    ```
4. Configure bootstrap.sh : <br>
    create file
    ```shell script
    touch bootstrap.sh
    ``` 
    insert code there
    ```shell script
    #!/usr/bin/env bash
    
    # Use single quotes instead of double quotes to make it work with special-character passwords
    PASSWORD='1234'
       
    # create project folder
    sudo mkdir -p "/var/www/axiom"
    
    # update / upgrade
    sudo apt-get update
    sudo apt-get -y upgrade
   
    # Install software-properties-common package to give us add-apt-repository package
    sudo apt-get install -y software-properties-common
    
    # Install latest nginx version from community maintained ppa
    sudo add-apt-repository ppa:nginx/stable
    
    # Update packages after adding ppa
    sudo apt-get update
    
    # Install nginx
    sudo apt-get install -y nginx
    
    # Check status
    sudo service nginx status
    
    # Start nginx if it is not already running
    sudo service nginx start
    
    # install php
    sudo apt-add-repository ppa:ondrej/php
    sudo apt-get update
    sudo apt install php7.4-fpm php7.4-common php7.4-mysql php7.4-xml php7.4-xmlrpc php7.4-curl php7.4-gd php7.4-imagick php7.4-cli php7.4-dev php7.4-imap php7.4-mbstring php7.4-opcache php7.4-soap php7.4-zip unzip -y
    
    # install mysql and give password to installer
    sudo debconf-set-selections <<< "mysql-server mysql-server/root_password password $PASSWORD"
    sudo debconf-set-selections <<< "mysql-server mysql-server/root_password_again password $PASSWORD"
    sudo apt-get -y install mysql-server
    
    uri = 'uri'
    is_args = 'is_args'
    args = 'args'
    sudo echo "server {
                          listen 80;
                          listen [::]:80;
                          root /var/www/axiom;
                      
                          index index.php;
                  
                          server_name axiom.dev www.axiom.dev;
                  
                          location / {
                                  try_files $uri /index.php$is_args$args ;
                          }
                  
                          location ~ \.php$ {
                                  include snippets/fastcgi-php.conf;
                                  fastcgi_pass unix:/run/php/php7.4-fpm.sock;
                          }
                  }" > /etc/nginx/sites-available/axiom
    sudo rm /etc/nginx/sites-enabled/default
    sudo ln -s /etc/nginx/sites-available/axiom /etc/nginx/sites-enabled/axiom
    sudo chmod 777 -R /var/www/
    
    # install git
    sudo apt-get -y install git
   
   
    ```
5. Up vagrant machine
    ```shell script
    vagrant up
    ```
