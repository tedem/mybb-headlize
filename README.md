<p align="center">
    <a href="https://github.com/tedem/mybb-headlize">
        <img src="https://i.imgur.com/wIZXJBn.png"
            width="200" height="200" alt="Headlize for MyBB">
    </a>
</p>

# Headlize for MyBB

**Headlize** is a MyBB plugin that automatically converts and saves thread titles into **APA-style title case**.
This ensures consistent, professional-looking thread titles throughout your forum.

APA-style [Title Case Capitalization](https://apastyle.apa.org/style-grammar-guidelines/capitalization/title-case) is a widely used style in academic and professional writing, making it ideal for forum discussions.

## Compatibility

Please refer to the table below for compatibility information:

| Plugin Version  | Supported MyBB Versions | PHP Version Required         |
|-----------------|-------------------------|------------------------------|
| 1.1.0           | 1.8.x.                  | >= 7.4                       |
| 1.2.0 and later | 1.8.x and 1.9.x         | >= 8.2                       |

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

## Install

1. Download the plugin from [Github](https://github.com/tedem/mybb-headlize/releases).
2. Upload the files in the "Upload" folder to the root directory of your forum with SCP (`scp` command.) or FTP (FileZilla, CuteFTP, etc.).
3. Install and activate the plugin named Headlize from the Admin CP → Configuration → (From left.) Plugins page.

## Uninstall

1. Uninstall the plugin named Headlize from the Admin CP → Configuration → (From left.) Plugins page.

## Update

1. First, perform the "Uninstall" section, then perform the "Install" (with new files) section.

## Usage

Once the plugin is installed and activated, it will automatically format thread titles to APA-style title case whenever a new thread is created or an existing thread title is edited.

### Ignoring

Words written between two underscores (`__`) are ignored, such as `__headlize__`.

Enjoy!

## Versioning

I use [SemVer](https://semver.org/) for versioning.

## License

[MIT](LICENSE)
