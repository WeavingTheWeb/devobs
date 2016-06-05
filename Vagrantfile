##################################################
# Generated by phansible.com
##################################################

#If your Vagrant version is lower than 1.5, you can still use this provisioning
#by commenting or removing the line below and providing the config.vm.box_url parameter,
#if it's not already defined in this Vagrantfile. Keep in mind that you won't be able
#to use the Vagrant Cloud and other newer Vagrant features.
Vagrant.require_version '>= 1.5'

Vagrant.configure('2') do |config|

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
    MANUAL_PROVISION = ENV['MANUAL_PROVISION'] ? true : false
    MANUAL_PUSH = ENV['MANUAL_PUSH'] ? true : false

    if MANUAL_PUSH
        config.push.define 'atlas' do |push|
            push.app = 'weaving-the-web/devobs-development'
            push.vcs = true
        end
    end

    config.ssh.forward_agent = true

    config.vm.box = 'weaving-the-web/devobs-development'

    IP_ADDRESS = '10.9.8.2'
    config.vm.network 'private_network', ip: IP_ADDRESS

    if ENV.key?('BOX_NAME')
        box_name = ENV['BOX_NAME']
    else
        box_name = 'devobs'
    end

    config.vm.provider :virtualbox do |v|
        v.name = box_name
        v.customize [
            'modifyvm', :id,
            '--name', box_name,
            '--memory', 4096,
            '--natdnshostresolver1', 'on',
            '--cpus', 1,
        ]
    end

    if MANUAL_PROVISION
        # See also http://foo-o-rama.com/vagrant--stdin-is-not-a-tty--fix.html
        config.vm.provision 'fix-no-tty', type: 'shell' do |s|
            s.privileged = false
            s.path = 'provisioning/packaging/scripts/fix-no-tty.sh'
        end
        config.vm.provision 'shell', path: 'provisioning/packaging/scripts/ensure-required-files-exist.sh'

        # If ansible is in your path it will provision from your HOST machine
        # If ansible is not found in the path it will be installed in the VM and provisioned from there
        if which('ansible-playbook')
            config.vm.provision 'ansible' do |ansible|
                ansible.playbook = 'provisioning/playbook.yml'
                ansible.inventory_path = 'provisioning/inventories/dev'
                ansible.limit = 'all'
            end
        else
            config.vm.provision :shell, path: 'provisioning/windows.sh', args: ['devobs']
        end
    end

    use_nfs = false
    use_rsync = true

    if ENV.key?('USE_NFS')
        use_nfs = ENV['USE_NFS']
    elsif ENV.key?('USE_RSYNC')
        use_rsync = ENV['USE_RSYNC']
    end

    synced_folder = '/var/deploy/devobs/releases/master'
    if use_nfs
        config.vm.synced_folder '.', synced_folder,
            type: 'nfs',
            map_uid: Process.uid,
            map_gid: Process.gid
    elsif use_rsync
        config.vm.synced_folder '.', synced_folder,
            type: 'rsync',
            rsync__exclude: ['.git/', 'app/cache', 'app/logs', 'parameters.yml', 'vendor/devobs', 'web/bundles']
    end

    config.vm.synced_folder '.', '/vagrant',
        type: 'nfs',
        map_uid: Process.uid,
        map_gid: Process.gid

    if COMPOSER_AUTH
        config.vm.provision 'file', source: COMPOSER_AUTH, destination: '~/.composer/auth.json'
    end
end
