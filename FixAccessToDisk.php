<?php

class FixAccessToDisk
{
    public function run()
    {
        /**
         * get and print system disks
         */
        $DataFromCli = shell_exec('df -h');
        $disks = $this->printDisks($DataFromCli);

        echo "\033[36m" . " write disk number...   ";

        $diskNumber = null;

        for ($i = true; $i === true;) {
            $diskNumber = readline();
            if (!empty($diskNumber)) {
                break;
            }

            echo "\033[46m write disk number...   ";
        }

        $PathToDisk = $this->getPath($disks, $diskNumber);

        $userName = $this->getUser();

        $this->changeOwnerDisk($userName, $PathToDisk);
        $this->changeAccessToDisk($PathToDisk);


    }

    public function printDisks($DataFromCli)
    {
        $disks = explode("\n", $DataFromCli);
        foreach ($disks as $index => $disk) {
            switch ($disk) {
                case "Filesystem      Size  Used Avail Use% Mounted on":
                    unset($disks[$index]);
                    break;
                case "":
                    unset($disks[$index]);
                    break;
                default:
                    $key = array_search($disk, $disks);
                    echo "\033[33m" . $key . ' ..... ' . $disk . "\n";
            }
        }

        return $disks;
    }

    public function getPath(array $disks, $diskNumber)
    {
        $explodeStringForDisk = $PathToDisk = explode(' ', $disks[(string) $diskNumber]);
        $pathToDisk = end($explodeStringForDisk);
        return $pathToDisk;
    }

    public function getUser()
    {
        $user = shell_exec('echo $USER');
        $user = substr($user, 0, -1);

        echo "Use user name and group " . "\033[31m" .  "[$user:$user]" . "\033[36m" . " [y / or just press enter end enter name]";
        if (readline() === "y") {
            $userName = $user;
        } else {
            echo "Write name for get access\n";
            $userName = readline();
        }

        return $userName;
    }

    public function changeOwnerDisk($userName, $PathToDisk)
    {
        echo "\n" . "\033[1;32m" . "sudo chown $userName:$userName $PathToDisk/";
        shell_exec("sudo chown $userName:$userName $PathToDisk/");
    }

    public function changeAccessToDisk($PathToDisk)
    {
        echo "\n" . "\033[1;32m" . "sudo chmod 777 -R $PathToDisk/" . "\033[0m" . "\n";
        shell_exec("sudo chmod 777 -R $PathToDisk/");
    }

}

$fixAccessToDisk = new FixAccessToDisk();
$fixAccessToDisk->run();
