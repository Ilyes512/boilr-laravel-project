# Windows Local Development Setup using WSL

> [!NOTE]
> **Assumptions made in this guide**:
>
> - You never used WSL on this machine (as in it was not installed earlier).
>   - If you have it installed, and assuming you know what your doing, then you might need to know that in this guide we
>     assumed there is only 1 (default) subsystem running. You might need to adjust the default subsystem or add a
>     specific (subsystem) target to the given commands.
> - You have a `C` drive and using that to install "software". Note that your `code/projects` can be located else where.

## Installing Windows Terminal and WSL

1. Install [Windows Terminal](https://apps.microsoft.com/detail/9n0dx20hk701?rtc=1&hl=nl-nl&gl=NL) and open it with a
   "PowerShell" tab.
2. Run in a "PowerShell" tab:

    ```powershell
    wsl --status
    ```

    *Should return nothing if no subsystem was installed before!*
3. Run in a "PowerShell" tab:

    ```powershell
    wsl --instal --distribution ubuntu-24.04
    ```

    *Windows will occasionally prompt you, after it's done restart the machine.*
4. After rebooting your Windows machine the terminal should automatically re-open and continue installing WSL.
5. It eventually prompts you for an username. The username does not have to match your Windows user! Choose an username
   with all lowercase alphanumeric characters. Something like: `linuxisthebest` or a more personal name `yourname007`.
6. It will also prompt you for a password for the user. Choose a password you like or use your username as the password
   as well. It does not really have to be secure, because your machine would already be protected by Windows own
   username and password (when you login to your machine). So choose something easy and fast to type.
7. You should now be in the VM's shell environment. You can verify this by running `uname` that should return Linux.
   Use `uname -a` for a more detailed output. Or even `lsb_release -a` to see the Ubuntu version.
8. You should be familiar with Posix/Bash, but the bare minimum commands are:
    - Display you current path (the location you are currently at): `pwd`
    - Display all directories and files in the current path: `ls -la`
    - Display the directories and files at a specific path (in the example the root of the subsystem): `ls -la /`
    - Move to your "home" directory: `cd ~`
    - Move to a specific path: `cd /tmp`
9. So the nice thing about using "Windows Terminal" app instead of the default "Command Prompt" app is that you now
   have the option to open a new tab that looks more like an actual Linux "terminal":
   ![Windows Terminal Tabs](assets/windows-setup/windows-terminal-tabs.png)
   You can safely close the first tab after adding the new "Ubuntu 24.04" tab. The "Ubuntu" tab can be
   referenced by the names "Linux", "Ubuntu", "WSL" or even "subsystem" tab and/or shell from now on.
10. Lets first update the subsystems OS: Ubuntu. Run the below command inside a "Ubuntu" tab. When it prompts you for
    your password, fill in the password you chose when you created the "Ubuntu" user above. Run in a "Ubuntu" tab:

    ```bash
    sudo apt update && sudo apt upgrade --yes
    ```

    - If you forgot your password you can reset it by opening a "PowerShell" tab:
        - Run `wsl -u root` to log into the "WSL" shell as the root user.
        - Run `passwd` or `passwd <username>` to reset you users password.
        - Close the tab using `exit` and re-open a new "Ubuntu" tab.
11. Optionally silence the Ubuntu daily "welcome" announcement in a "Ubuntu" tab with:

    ```bash
    touch $HOME/.hushlogin
    ```

## Installing and configuring Git

1. Lets configure Git and enable you to download Github repo's both on Windows and in Subsystem's by installing "Git for
   Windows" that by default will also install the required "GIt Credential Manager".
   [Download](https://gitforwindows.org) and follow the install instructions and make sure you pay attention to the
   default (git) editor, because this is set to Vim by default.
2. Let's make sure credentials are shared between Windows and your subsystems by configuring "Git Credential Manager" on
   both sides. Note that this only applies to `https` credentials and not to `ssh` keys:
    1. Inside your "Ubuntu" tab execute:

        ```bash
        git config --global credential.helper "/mnt/c/Program\ Files/Git/mingw64/bin/git-credential-manager.exe"
        ```

        *Note that your path might be different.*
    2. Inside a "PowerShell" tab execute the following command:

        ```powershell
        git config --global credential.helper wincred
        ```

3. You can now just clone a project using the `https` URL. When credentials are needed a popup will show that will allow
   you to login to for example GitHub. Other platforms are also supported. See the repository for more info:
   [git-ecosystem/git-credential-manager](https://github.com/git-ecosystem/git-credential-manager).
4. Configure your git username and email inside a "Ubuntu" shell:
    1. Set you username using:

        ```bash
        git config --global user.name "Your Name"
        ```

    2. Set your email using:

        ```bash
        git config --global user.email "youremail@domain.com"
        ```

## Installing PHP and Composer inside the default subsystem

Run inside a "Ubuntu" tab:

```bash
sudo apt-get update && sudo apt-get install -y php8.3-cli php8.3-curl php8.3-zip php8.3-xml unzip 7zip composer
```

## Setup and configure Docker

1. Download and install "Docker Desktop for Windows" at: <https://docs.docker.com/desktop/install/windows-install>
2. You probably will be asked to restart your machine. Please do that and continue after the restart.
3. In the Docker window it will ask you "Sign up". That is currently not needed and just press
   "Continue without signing in".
4. Inside a "Ubuntu" tab try running both `docker version` and `docker info`.
    - If the docker commands are not found check if "Docker Desktop for Windows" is running! You can enable it to
    automatically start on login in the settings.
    - By default "Docker Desktop for Windows" will setup Docker inside your default Subsystem. However, if you add more
    subsystems you will need to enable it in the "Docker Desktop for Windows" settings.

## Optional: Installing VSCode with Remote WSL extension

VSCode got excellent WSL support (because Windows, GitHub and VSCode are all owned by the same company).

1. Download and install VSCode: <https://code.visualstudio.com>.
2. Install the VSCode extension: `ms-vscode-remote.remote-wsl`.
3. See <https://code.visualstudio.com/docs/remote/wsl> for more info.

## Optional: Way of working using WSL

Instead of checking out your code somewhere on your Windows machine you checkout the project inside your subsystem.

1. Setup "Docker Desktop for Windows" to auto-start on login.
2. Open up "Windows Terminal" and configure your default profile and set it to "Ubuntu 20.24" so when you open up the
   app. You already in a "Ubuntu" tab.
3. Create a `code` directory in your "Ubuntu" user's home directory. Run in a "Ubuntu" tab:

    ```bash
    mkdir ~/code
    ```

4. Clone a project inside the `code` directory. Run in a "Ubuntu" tab:

    ```bash
    cd ~/code
    ```

    ```bash
    git clone <https://your_project>
    ```

5. If you installed `VSCode` and also installed the "remote wsl" extension you can open it in code using:

    ```bash
    code ~/home/code/your_project
    ```

    Know that you can also navigate to the directory first and then open the current directory using a `.` (dot):

    ```bash
    code .
    ```

## Tips

- Inside a WSL shell you can use `explorer.exe .` to open the current directory in a "Windows File Explorer" window.
- When you are in a "PowerShell" tab you can execute a subsystem command within the subsystem using `wsl <cmd>`. For
  example `wsl pwd` to see the path inside subsystem.
- In the "Windows Terminal" you can make the "Ubuntu shell the default profile in settings.
- Subsystems can be exported and imported… that means you can have a "base" setup and create a backup so you can easily
  recreate you work environment.

## Handy tools and/or links

- [Windows Terminal](https://apps.microsoft.com/detail/9n0dx20hk701?rtc=1&hl=nl-nl&gl=NL)
- [Git Credential Manager](https://github.com/git-ecosystem/git-credential-manager)
- [Git for Windows](https://gitforwindows.org/)
- [Microsoft Setup WSL guide](https://learn.microsoft.com/en-us/windows/wsl/setup/environment)
- [How to open a project in PHPStorm that's inside your Subsystem](https://www.jetbrains.com/help/phpstorm/how-to-use-wsl-development-environment-in-product.html#open-a-project-in-wsl)
