# Vagrant Setup
**TODO**: Add short description.

## Requirements
A virtual machine (VM) provider like *Hyper-V* or *VirtualBox* must be installed. Furthermore, there should be at least **2GB** of free RAM available in order for the server to work properly.

## Install Vagrant on Linux (debian based)

Install Vagrant with the package manager:
```
sudo apt-get install vagrant
```

## Install Vagrant on macOS

Download and install Vagrant from [HashiCorp](https://www.vagrantup.com/downloads.html).

## Install Vagrant on Windows 10

Download and install Vagrant from [HashiCorp](https://www.vagrantup.com/downloads.html).
If you wish to use *VirtualBox* instead of *Hyper-V* you need to disable it via the powershell:
```
$ Disable-WindowsOptionalFeature -Online -FeatureName Microsoft-Hyper-V-All
```

## Vagrant Commands
- [**vagrant init**](https://www.vagrantup.com/docs/cli/init.html)
This initializes the current directory to be a Vagrant environment by creating an initial *Vagrantfile* if one does not already exist.

- [**vagrant up**](https://www.vagrantup.com/docs/cli/up.html)
This command creates and configures guest machines according to your *Vagrantfile*.

- [**vagrant halt**](https://www.vagrantup.com/docs/cli/halt.html)
This command shuts down the running machine Vagrant is managing.

- [**vagrant suspend**](https://www.vagrantup.com/docs/cli/suspend.html)
This suspends the guest machine Vagrant is managing, rather than fully shutting it down or destroying it.

- [**vagrant resume**](https://www.vagrantup.com/docs/cli/resume.html) 
This resumes a Vagrant managed machine that was previously suspended, perhaps with the suspend command.

- [**vagrant ssh**](https://www.vagrantup.com/docs/cli/ssh.html) 
This will SSH into a running Vagrant machine and give you access to a shell. It will either use the default user *vagrant* or the one specified in the *Vagrantfile* wit the parameter `config.ssh.username`. 

- [**vagrant reload**](https://www.vagrantup.com/docs/cli/reload.html) 
The equivalent of running a `halt` followed by an `up`. This command is usually required for changes made in the Vagrantfile to take effect.

- [**vagrant destroy**](https://www.vagrantup.com/docs/cli/destroy.html) 
This command stops the running machine Vagrant is managing and destroys all resources that were created during the machine creation process. 

## Linux & macOS Setup Script

In order to setup the Webserver for the websec project (**TODO**: add link to GitHub) simply run the ```install_server.sh``` script and when prompted choose your VM provider.

## Windows Setup Script 

To run the install script you need to set an exception from the default windows security settings. This is necessary since the script is loaded from a remote source and has no digital signature.
```
$ powershell -ep RemoteSigned -file install_server_win.ps1
```
Also, powershell needs to be executed with admin rights in order to work properly with vagrant and Hyper-V.

In order to setup the Webserver for the websec project (**TODO**: add link to GitHub) simply run the ```install_server_win.ps1``` script and when prompted choose your VM provider *(Hyper-V or VirtualBox)*.
 
## Post Installation

**TODO:** what is to do after first installation? ssh? provisioning?
 
## How it Works?

**TODO:** What happens when the script is executed and how is the webserver provisioned? How does the Git integration work?
 