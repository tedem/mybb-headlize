<p align="center">
    <a href="https://github.com/tedem/mybb-headlize">
        <img src="https://i.imgur.com/wIZXJBn.png"
            width="200" height="200" alt="Headlize for MyBB">
    </a>
</p>

# Headlize for MyBB

Automatically converts and saves thread titles in APA-style title case.

**Related source:** https://apastyle.apa.org/style-grammar-guidelines/capitalization/title-case

Entered title:

```
this title was created as a test for the mybb __headlize__ plugin
```

Format saved in the database:

```
This Title Was Created as a Test for the MyBB headlize Plugin
```

Words written between two underscores (`__`) are ignored, such as `__headlize__`.

## Details

- **Version:** 1.0.1
- **MyBB Versions:** 1.8.x

## Install

1. Download the plugin from [Github](https://github.com/tedem/mybb-headlize/releases).
2. Upload the files in the "Upload" folder to the root directory of your forum with SCP (`scp` command.) or FTP (FileZilla, CuteFTP, etc.).
3. Install and activate the plugin named Headlize from the Admin CP → Configuration → (From left.) Plugins page.

## Uninstall

1. Uninstall the plugin named Headlize from the Admin CP → Configuration → (From left.) Plugins page.

## Update

1. First, perform the "Uninstall" section, then perform the "Install" (with new files) section.

## Usage

The plugin works with **English** and **Turkish** words. You should add words specific to your language as shown below.

Code block that needs to be edited:

https://github.com/tedem/mybb-headlize/blob/6f4bf9b563d86b4a914bf147f6015e91fa993474/inc/plugins/headlize.php#L161-L168

### Ignoring

Words written between two underscores (`__`) are ignored, such as `__headlize__`.

Enjoy!

## Contributing Guidelines

Please review our [contributing guidelines](https://github.com/tedem/tedem/blob/main/docs/CONTRIBUTING.md) to learn about our submission process, coding standards, and more.

## Versioning

I use [SemVer](https://semver.org/) for versioning.

## Authors

- **Medet Erdal** - _Initial work_ - [tedem](https://github.com/tedem)

## License

[MIT](LICENSE)
