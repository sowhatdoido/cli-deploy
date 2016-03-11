# Pusheen
Pusheen is a command line tool to deploy code to various environments using S/FTP. Think of it as a virtual cat assistant that looks up file changes via git and deploys it for you. Thanks Buddy!

![Pusheen at work](http://i.imgur.com/pCzzsma.gif "Pusheen at work")

Why S/FTP? Ideally we'd always have shell access to a remote server that allows us to quickly synchronize (via rsync, git hooks, etc) our local environments with production... unfortunately that isn't always the case.
In my experience, S/FTP will generally be available regardless of whether or not a client's hosting provider allows shell access, making it a good candidate for general purpose use.
