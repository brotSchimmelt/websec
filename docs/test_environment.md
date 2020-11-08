# Test Environment Setup (Vagrant)
---

This is the test environment for the WebSec hacking platform. Vagrant is used to setup an Ubuntu (**18.04 LTS** or **20.04 LTS**) test server.

## Requirements
Vagrant (>= **2.2.5**) must be installed. Also, a virtual machine (VM) provider like *Hyper-V* or *VirtualBox* must be installed on the host system. Furthermore, there should be at least **2GB** of free RAM available in order for the server to work properly.

## Install Vagrant on Linux (debian based)

Install Vagrant with the package manager:

```shell
$ sudo apt-get install vagrant
```

## Install Vagrant on macOS

Download and install Vagrant from [HashiCorp](https://www.vagrantup.com/downloads.html).

## Install Vagrant on Windows 10

Download and install Vagrant from [HashiCorp](https://www.vagrantup.com/downloads.html).
If you wish to use *VirtualBox* instead of *Hyper-V* you need to disable it via the powershell:

```shell
$ Disable-WindowsOptionalFeature -Online -FeatureName Microsoft-Hyper-V-All
```

## Linux & macOS Setup Script

In order to setup the web server for the hacking platform, simply run the ```setup_vagrant_server.sh``` script and when prompted choose your VM provider.

If you choose to use Virtualbox as VM provider, you are asked during the setup to select your network. In this case, select the your external network connection.

## Windows Setup Script 

To run the install script you need to set an exception from the default windows security settings. This is necessary since the script is loaded from a remote source and has no digital signature.

```shell
$ powershell -ep RemoteSigned -file setup_vagrant_server_win.ps1
```
Also, powershell needs to be executed with admin rights in order to work properly with vagrant and Hyper-V.

In order to set up the web server for the hacking platform simply run the ```setup_vagrant_server_win.ps1``` script and when prompted choose your VM provider *(Hyper-V or VirtualBox)*.

## Post Installation

In order to start the hacking platform, you can simply run the ```setup_docker.sh``` script or follow the manual setup instructions in the Docker README.

## Vagrant Commands

Some useful Vagrant commands.

[`$ vagrant init`](https://www.vagrantup.com/docs/cli/init.html)
This initializes the current directory to be a Vagrant environment by creating an initial *Vagrantfile* if one does not already exist.

<br>

[`$ vagrant up`](https://www.vagrantup.com/docs/cli/up.html)
This command creates and configures guest machines according to your *Vagrantfile*.

<br>

[`$ vagrant halt`](https://www.vagrantup.com/docs/cli/halt.html)
This command shuts down the running machine Vagrant is managing.

<br>

[`$ vagrant suspend`](https://www.vagrantup.com/docs/cli/suspend.html)
This suspends the guest machine Vagrant is managing, rather than fully shutting it down or destroying it.

<br>

[`$ vagrant resume`](https://www.vagrantup.com/docs/cli/resume.html) 
This resumes a Vagrant managed machine that was previously suspended, perhaps with the suspend command.

<br>

[`$ vagrant ssh`](https://www.vagrantup.com/docs/cli/ssh.html) 
This will SSH into a running Vagrant machine and give you access to a shell. It will either use the default user *vagrant* or the one specified in the *Vagrantfile* wit the parameter `config.ssh.username`. 

<br>

[`$ vagrant reload`](https://www.vagrantup.com/docs/cli/reload.html) 
The equivalent of running a `halt` followed by an `up`. This command is usually required for changes made in the Vagrantfile to take effect.

<br>

[`$ vagrant destroy`](https://www.vagrantup.com/docs/cli/destroy.html) 
This command stops the running machine Vagrant is managing and destroys all resources that were created during the machine creation process. 
