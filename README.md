### Levanter Md in Termux <Ubunt>

Simple deployment

## Setup
1. First above all, install Termux Apk. Click [Here](https://termux.en.uptodown.com/android/post-download/106885413) to download.

2. Open Termux App info and Allow access to Files and Media:

3. Install repo

             pkg install root-repo

4. Install X11

             pkg install x11-repo

5. Update and upgrade Termux packages (Run command "y" if paused):

             apt update && apt upgrade -y

6. Install required packages:

             pkg install wget openssl-tool proot -y
   
7. Downloading ubuntu Setup file:

             wget https://raw.githubusercontent.com/EXALAB/AnLinux-Resources/master/Scripts/Installer/Ubuntu/ubuntu.sh

8. Give the Executable Permission:
   
             chmod +x *

9. Run the Setup file. This setup process will take 10–15 minutes, depending on your internet speed:
   
             ./ubuntu.sh

10. Run Ubuntu / Open Ubuntu:
   
             bash start-ubuntu.sh

11. Install git, ffmpeg, and curl:
   
             apt -y update && apt -y upgrade
             apt -y install git ffmpeg curl
    
12. Install nodejs:
   
             curl -fsSl https://deb.nodesource.com/setup_lts.x | bash - && apt -y install nodejs

13. Update nodejs version:
   
             npm install -g npm@10.5.1

14. Install yarn:
   
             npm install -g yarn

15. Install pm2:
   
             yarn global add pm2

16. Clone the repository and install packages (Replace "botName" your your preferred bot name in lowercase):
   
             git clone https://github.com/lyfe00011/whatsapp-bot-md botName
             cd botName
             yarn install --network-concurrency 1

17. Obtain Session_ID from [Levanter](https://qr-hazel-alpha.vercel.app/session):

18. Enter Environment Variables: Edit them to your preference:

             echo "SESSION_ID = Session_Id
             PREFIX = .
             STICKER_PACKNAME = Muindi
             ALWAYS_ONLINE = true
             RMBG_KEY = null
             LANGUAG = en
             WARN_LIMIT = 1
             FORCE_LOGOUT = false
             BRAINSHOP = 159501,6pq8dPiYt7PdqHz3
             MAX_UPLOAD = 200
             REJECT_CALL = true
             SUDO = 254739642355,254115783375
             TZ = Africa/Nairobi
             VPS = true
             AUTO_STATUS_VIEW = no-dl
             SEND_READ = true
             AJOIN = true
             DISABLE_START_MESSAGE = false
             PERSONAL_MESSAGE = null" > config.env

20. To save, press Ctrl + O then press Enter, press Ctrl + X to exit.

21. Start the Bot: Replace botName with your actual bot name: {After this, your bot should start running}
    
            pm2 start . --name botName --attach --time

 - You can leave it at this point, but if you want the bot to run even on offline mode: Do as below

21. Click acquire Wakelock in the Termux notification to enable it run in background. Exit both the ubuntu & Termux

22. After closing, open Termux again and navigate to Ubuntu:
    
            bash start-ubuntu.sh

23. Open your bot folder:
    
            cd botName

24. Start the bot:(Do the necessary replacements):
    
            pm2 start . --name botName --attach --time

25. Stop bot:(Incase you wanna stop it):
    
            pm2 stop botName




### Thanks To

- [Ndeleva](https://github.com/Ndelevamutua) for [Backend](https://github.com/Ndelevamutua/whatsapp)
- [Muindi](https://github.com/muindi6602) for [Frontend](https://muindi6602.github.io/)

### Get me on:

- [WhatsApp](https://wa.me/254115783375)
