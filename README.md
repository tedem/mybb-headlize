<p align="center">
    <a href="https://github.com/tedem/mybb-headlize">
        <img src="https://i.imgur.com/wIZXJBn.png"
            width="200" height="200" alt="Headlize for MyBB">
    </a>
</p>

# Headlize for MyBB

**Headlize** is a MyBB plugin that automatically converts and saves thread titles into **APA-style title case**.
This ensures consistent, professional-looking thread titles throughout your forum.

**Official reference for APA title case:**
[APA Title Case Guidelines](https://apastyle.apa.org/style-grammar-guidelines/capitalization/title-case)

## Features

- Converts thread titles to APA-style title case automatically.
- Ignores words enclosed between double underscores (`__word__`) to preserve specific terms.
- Saves the formatted title directly in the MyBB database.
- Supports acronyms, technical terms, and common exceptions to ensure correct capitalization (e.g., `API`, `PHP`, `MyBB`).

---

## How It Works

The plugin processes thread titles in a few steps to ensure APA-style title case while preserving special terms.

### Step 1: User enters a title

Example user input:

```text
this title was created as a test for the mybb __headlize__ plugin
```

### Step 2: Headlize applies title case

- Converts major words to APA-style title case.
- Preserves words from the exceptions list (acronyms, technical terms, frameworks, etc.).
- Leaves words wrapped in double underscores (`__`) unchanged.

### Step 3: Result saved to the database

Formatted title saved in the database:

```text
This Title Was Created as a Test for the MyBB headlize Plugin
```

✅ **Note:** MyBB is preserved as defined in the exceptions list, and `__headlize__` remains lowercase because it was wrapped in double underscores.

## Details

- **Plugin Version:** 1.1.0
- **Supported MyBB Versions:** 1.8.x and later
- **Minimum PHP Version Required:** 7.4+

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

https://github.com/tedem/mybb-headlize/blob/a4f7d247e8b5dbf37bdaf0de35e9074b43f0bee7/inc/plugins/headlize.php#L164-L176

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
