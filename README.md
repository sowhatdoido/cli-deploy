# cli-deploy
Built to deploy code to various environments using S/FTP, using git to track the files changed.

Why S/FTP? Ideally we'd always have shell access to a remote server that allows us to quickly synchronize (via rsync, git hooks, etc) our local environments with production... unfortunately that isn't always the case.
In my experience, S/FTP will generally be available regardless of whether or not a client's hosting provider allows shell access, making it a good candidate for general purpose use.
