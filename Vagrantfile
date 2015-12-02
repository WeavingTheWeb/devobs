##################################################
# Generated by phansible.com
##################################################

#If your Vagrant version is lower than 1.5, you can still use this provisioning
#by commenting or removing the line below and providing the config.vm.box_url parameter,
#if it's not already defined in this Vagrantfile. Keep in mind that you won't be able
#to use the Vagrant Cloud and other newer Vagrant features.
Vagrant.require_version ">= 1.5"

IP_ADDRESS = "10.9.8.2"

# Check to determine whether we're on a windows or linux/os-x host,
# later on we use this to launch ansible in the supported way
# source: https://stackoverflow.com/questions/2108727/which-in-ruby-checking-if-program-exists-in-path-from-ruby
def which(cmd)
    exts = ENV['PATHEXT'] ? ENV['PATHEXT'].split(';') : ['']
    ENV['PATH'].split(File::PATH_SEPARATOR).each do |path|
        exts.each { |ext|
            exe = File.join(path, "#{cmd}#{ext}")
            return exe if File.executable? exe
        }
    end
    return nil
end

COMPOSER_AUTH = ENV['COMPOSER_AUTH'] ? ENV['COMPOSER_AUTH'] : nil

Vagrant.configure("2") do |config|
    config.push.define "atlas" do |push|
      push.app = "weaving-the-web/devobs"
    end

    config.ssh.forward_agent = true

    config.vm.box = "weaving-the-web/devobs"
    config.vm.network "private_network", ip: IP_ADDRESS
    config.vm.provider :virtualbox do |v|
        v.name = "devobs"
        v.customize [
            "modifyvm", :id,
            "--name", "devobs",
            "--memory", 4096,
            "--natdnshostresolver1", "on",
            "--cpus", 1,
        ]
    end

    # See also http://foo-o-rama.com/vagrant--stdin-is-not-a-tty--fix.html
    config.vm.provision "fix-no-tty", type: "shell" do |s|
        s.privileged = false
        s.inline = "sudo sed -i '/tty/!s/mesg n/tty -s \\&\\& mesg n/' /root/.profile"
    end
    config.vm.provision "shell", path: "provisioning/scripts/ensure-required-files-exist.sh"

    if COMPOSER_AUTH
        config.vm.provision "file", source: COMPOSER_AUTH, destination: "~/.composer/auth.json"
    end

    # If ansible is in your path it will provision from your HOST machine
    # If ansible is not found in the path it will be instaled in the VM and provisioned from there
    if which('ansible-playbook')
        config.vm.provision "ansible" do |ansible|
            ansible.playbook = "provisioning/playbook.yml"
            ansible.inventory_path = "provisioning/inventories/dev"
            ansible.limit = 'all'
        end
    else
        config.vm.provision :shell, path: "provisioning/windows.sh", args: ["devobs"]
    end
end
