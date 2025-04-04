# Plugin Changelog – [BxB Layout Dashboard](https://github.com/CVLTIK/BxB-Layout-Dashboard)

This document records all notable changes made to the BxB Layout Dashboard plugin, including new features, updates, and bug fixes.

## [UNRELEASED]
### Added

### Changed

### Fixed

### Requests

- Define Categories to split snippets and plugins https://wpcode.com/

---

## [1.0.2] – 2025-04-04
### Added

* Implemented **Parsedown** to improve the display of Markdown (`.md`) files within the admin dashboard.
* Added a statement in the main plugin file that will allow th code to keep rendering even if the file is misnamed or non existant. 

### Changed

* Refactored the plugin's structure to use a **modular approach**, where multiple separate files handle different pages, instead of managing everything within a single file.
* Adjusted the necessity of an admin page, making the plugin more streamlined in its navigation and management.
* Dashboard "Settings" to "Dashboard" to begin propir scaffolding.
* Change request once to if exists run in the bxb dashboard php.

### Fixed

* Resolved an issue where the plugin would fail to activate, leading to a **500 internal server error**.
* Fixed incorrect formatting of `.md` files when displayed in the admin panel.
* Linking on the **README** file. 
* Modularity of the **Documentation Module** to make it fasier to mantain and add to.

---

## [1.0.1] – 2025-04-02

### Added

* Verified **compatibility** with the latest tested version of WordPress.
* Introduced a **changelog (`Plugin-Changelog.md`)** to document all updates and changes.
* Created a **README file (`README.md`)** for better documentation of the plugin's purpose and setup.
* Developed the **initial plugin script**, establishing the core functionality.
* Implemented a **WordPress Activation Hook**, ensuring proper setup when the plugin is activated.
* Added an **Admin Hook**, allowing better integration within the WordPress dashboard.
* Set up **script and style queuing**, optimizing the way assets are loaded.
* Began integrating **Advanced Custom Fields (ACF)** to enhance customization options.
* Enabled **setting saving**, allowing users to store and manage their preferences.
* Implemented an **uninstall cleanup** process to remove any remaining data when the plugin is deleted.

### Changed

* Converted the **changelog from a `.txt` file to a `.md` file** for improved readability.
* **Reorganized the README file**, adjusting the order of content, adding relevant icons, and improving internal linking.
* **Renamed the plugin**, making the name more descriptive and aligned with its functionality.

### Fixed

* **Rebased the file structure**, ensuring the plugin appears correctly in the WordPress plugin dashboard and can be activated without errors.
* Fixed **broken formatting in the README file**, making it display properly within WordPress.

---

## [1.0.0] – 2025-03-31

### Added

* Set up **Kinsta SSH Terminal access**, allowing command-line management of the site.
* Established **initial Git integration**, providing version control for the project.
* Configured **core development tools** within VS Code, including support for [GitLens](https://marketplace.visualstudio.com/items?itemName=eamodio.gitlens), improving collaboration and code tracking.
