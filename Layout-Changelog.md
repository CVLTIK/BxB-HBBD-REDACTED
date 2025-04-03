## Layout 11

---

### 11.6.31 - 03-14-2025

- Added Boost services Parent Page

### 11.6.30 - 03-06-2025

- Added a the “Back to top” button snippet. If toggled on, the button appears on every page. CSS settings in “buttons.scss” tab.

### 11.6.29 - 03-04-2025

- Updated the Post Duplicator plugin’s permissions settings to allow the PPC Editor role the ability to duplicate landing pages. A recent update to the Post Duplicator plugin had removed that ability.

### 11.6.28 - 02-25-2025

- Added Max Mega Menu

### 11.6.27 - 01-27-2025

- Updated the ACF Review URL to be /leave-review/ under Site Settings along with the default instructions.

### 11.6.26 - 01-20-2025

- Added custom JS to the Financing page to prevent submission of the Loan Calculator form via the Enter key.

### 11.6.25 - 01-14-2025

- [display-cities-homepage] added to display city names with a `,` for the home page. Shortcode displays: City 1, City 2, City 3, (no abbreviated state).  An example of use would be `Serving [display-cities-homepage] and Surrounding Areas`  would display `Serving City1, City3, City3, City4, and Surrounding Areas`

### 11.6.24 - 01-09-2025

- Snippet added to shorten review length on Parent service pages.

### 11.6.23 - 12-11-2024

- Removed ‘Populate Anything’ Gravity Perk from the city field on the Review form, and the city field is now populated with taxonomy (as it was before 11.6.18).  A field for an SEO Title was added to the Service Cities taxonomy. The title on the city page is now a conditional, if there is an SEO title then that shows, if not, the taxonomy title shows, which is City, ST.

### 11.6.22 - 10/22/2024

- Added “WP Rocket: Exclude Lazy Rendering” code snippet. This snippet prevents a site’s footer, where the Mobile CTA Buttons are located, from lazy rendering, which was creating inconsistencies in the loading of the buttons.

### 11.6.21 - 10/10/2024

- Videos added to content

### 11.6.20 - 9/20/2024

- Replaced the “ActiveCampaign Postmark (Official)” plugin with “Gravity SMTP”.

### 11.6.19 - 7/25/2024

- Added location page (draft) and added shortcode for listing cities by location.

### 11.6.18 - 7/12/2024

- Updated the BxB Review Form’s “City” field to use Populate Anything to populate city names without any SEO additions appearing in the city’s name. See [BxB Review System: Populate Anything](https://www.notion.so/BxB-Review-System-Populate-Anything-4270ad47e7984deea675f5199137b486?pvs=21) for reference.

### 11.6.17 - 6/25/2024

- New page added to plumbing services: Tub and Shower Features.

### 11.6.16 - 6/21/2024

- Migrated ACF Options page settings from a snippet in Code Snippets to the ACF plugin.
- Replaced the multiple scripts added in version 11.6.14 (that replaced the AddFunc Head & Footer Code plugin) with a single ACF options page, Site Settings → Scripts.

### 11.6.15 - 6/4/2024

- Changed city page template map to use `[current-page-city], [current-page-state]` instead of the `City, ST` field.  A problem with using City, ST was noticed when CO (for Colorado) was bring up locations of companies. Problem is solved by using full state name.

### 11.6.14 - 5/30/2024

- Removed the AddFunc Head & Footer Code plugin and replaced its functionality with scripts added to the Code Snippets plugin.
- Added a CTA header for city pages and excluded city pages from the default CTA. The CTA on the other pages was not pulling in the correct field for the title of the page.
- Added a field under Options > Service Content  called `Services Company Offers` . This is a place to fill in the general services that the company offers, such as HVAC and Plumbing, or Electrical. It will be used throughout the site with the shortcode [services-company-offers]. It will appear in the customer notification for the BxB review form.
- Added two new pages of Syndicated content: Swimming Pool Heat Pumps (HVAC), and Portable Toilets (Plumbing)
- Added county as a field under Top cities. Shortcode added [county]

### 11.6.13 - 5/23/2024

- Made Financing page ‘no-index’ so that bots are not sent to financing applications.
- Published referral form and added to the menu by default.
- Drafted Electrical and Plumbing in the HVAC menu.

### 11.6.12 - 5/16/2024

- Financing verbiage changed to comply with Wells Fargo.
- Changed wording of mention of maintenance plans on commercial pages to say that customized maintenance plans may be created, since our residential plans do not apply.
- Added a shortcode for social icons, mainly to be used in the footer. Added a group to the Options menu to keep all the social links and image urls.
- Second phone number (with shortcode) added.
- Took financing off commercial pages since it’s quite rare that a client offers commercial financing, and if they do it’s a custom plan.

### 11.6.11 - 5/10/2024

- Added all available services to the Service Items taxonomy, as well as a category. Services not offered can be deleted. This is easier than adding them and setting up the gravity form.
- Created a menu that includes all services. If the pages are deleted, they will be highlighted in red and simple to remove in bulk.
- Changed the BxB Review gravity form customer notification email wording from `HVAC` to  `services`  to accommodate all types of services that the clients offer.

### 11.6.10 - 04/23/2024

- Refactored the `[display_cities]` shortcode to display the City, ST custom field instead of the term (taxonomy) name, since the term name will be customized for SEO purposes. For example, the City, ST could be Rochester, MI, and the term name could  be HVAC Services in Rochester, MI.

### 11.6.9 - 04/22/2024

- Added ability to edit Rank Math `serviceType` schema in the Pages admin columns.

### 11.6.8 - 03/22/2024

- Added saved text modules for the default and landing page phone numbers.

### 11.6.7 - 03/12/2024

- Refactored the `[display_cities]` shortcode to display cities as a list.

### 11.6.6 - 02/28/2024

- Set all service pages to use the service schema type.

### 11.6.5 - 02/21/2024

- Updated “BxB Landing Page 2.0 Functions” snippet to correctly display custom image mobile banner.

### 11.6.4 - 02/15/2024

- Added saved filter views in Admin Columns for all product types in the HVAC Product Feed.

### 11.6.3 - 02/06/2024

- Converted Landing Page 2.0 phone numbers to use [E.164 format](https://www.notion.so/Landing-Page-2-0-E-164-Phone-Number-302342ec413d4bdcaf2e33be873f6e31?pvs=21).

### 11.6.2 - 01/31/2024

- Added content for Commercial Electrical parent page.
- Added content for Commercial Plumbing parent page.
- Added content for Residential Electrical parent page.

### 11.6.1 - 01/26/2024

- Added content for Commercial HVAC Services parent page.
- Added content for Residential Plumbing Services parent page.

### 11.6.0 - 01/03/2024

- Added content for Residential HVAC Services parent page.

### 11.5.2 - 11/30/2023

- Added service pages: Pool Heater Service and Light Commercial.

### 11.5.1 - 11/15/2023

- Updated the ACF “Landing Page Options” field group to include options for a custom banner image for LP 2.0.
- Updated the “BxB Landing Page 2.0 Functions” code snippet with logic to display a custom banner image for LP 2.0 if selected.

### 11.5.0 - 11/6/2023

- Shortcodes [city-1], [city-2] and [city-3] display the top 3 city names, [linked-city-1], [linked-city-2] and [linked-city-3] are links to the top 3 cities.

### 11.4.0 - 11/2/2023

- Added custom schema template to Rank Math for `areaServed`.
- Removed the `areaServed` snippet from the Code Snippets plugin.
- Removed `serviceType` schema property from the “Aggregate Review Count + Stars + Schema (v2)” snippet.
- Added syndicated content page in residential electrical: Smoke Detectors and Carbon Monoxide (CO) Alarms

### 11.3.0 - 10/20/2023

- Added Font Awesome plugin and updated review system stars from rounded to sharp.
- Added review title, stars, and date to Landing Page 2.0 reviews
- Added new CTA Title Banner layouts (default + 5 Establish-themed)
- Removed service page banners from service pages
- Removed service parent page service banner images
- Removed service page banner custom fields
- Added global row for page-specific testimonials

### 11.2.7 -10/12/2023

- added Duct Cleaning city versions 1-15 and Trenchless Pipe Bursting (plumbing) service page

### 11.2.6 -10/4/2023

- Removed the mobile number field from the “Connect: Service Follow-Up” form.
    - BxB is no longer offering Twilio integration.

### 11.2.5 - 09/19/2023

- Added Feature - Deactivate and Delete snippet to Deactivate and Delete plugins.

### 11.2.4 - 09/6/2023

- changed “Page type” custom field to “Service type” because it’s used on city content, service items and pages. Also changed it to a checkbox instead of a radio button so that multiple selections can be made. Added “custom” and “general”. Custom can be used to flag pages that deviate from layout which can be useful for refreshes and keeping track of pages that come from clients.

### 11.2.3 -08/31/2023

- Increased the `WP_MEMORY_LIMIT` from `256M` to `512M`.

### 11.2.2 - 08/30/2023

- New Water Heater page content for Syndicated B, C and D. Consolidated the Tankless, Tanked and Water Heater Repair to one service page.

### 11.2.1 - 8/28/2023

- Removed the Uber Login Logo plugin.
- Added “WordPress Login Page Logo” script to Code Snippets. Replaces functionality of the Uber Login Logo plugin.

### 11.2.0 - 8/11/2023

- Added [Landing Page 2.0 Overview](https://www.notion.so/Landing-Page-2-0-Overview-e202b714e3f142c49134c91de8d92abf?pvs=21) to the layout.

### 11.1.3 - 7/10/2023

- Added Electrical topics to city page content.
- Added a filterable column to the city content dashboard for topic.

### 11.1.2 - 6/20/2023

- Updated review system star icons for Post Type Builder and CSS due to change in the way Post Type Builder displays stars. More information about the change can be found here: [(PTB) Review Stars Outline Only](https://www.notion.so/PTB-Review-Stars-Outline-Only-e2bc897196f442919ca37306f6fa04bc?pvs=21)

### 11.1.1 - 5/15/2023

- Added `xxx-bxb-ppc` user to allow the PPC department access to a site’s head and footer codes to add tracking snippets for campaigns.
- Removed “Service Content” from Editor role menu.
- Uninstalled UpdraftPlus plugin. ManageWP will be used for offsite backups.

### 11.0.4 - 5/2/2023

- Deleted product templates, pages will be imported instead. Most updated pages are included in the folder referenced in the [HVAC RSS feed Guide](https://www.notion.so/HVAC-Product-RSS-Feed-Layout-10-64af270367fb470eae65fc314aeb1171?pvs=21).
- For easier reading and filtering, added “Service Type” filter for Service Items taxonomy. (same as Page type). There are about 60 services, and sorting by service type makes it easier to check the list.

### 11.0.3 - 04/26/2023

- Added unique services and insulation services.
- Shortcode for internal linking removed from HVAC pages.
- Added CSS for Service Area page (2 columns on tablet, 1 column on mobile).
- Added Page type: Residential Insulation and Unique

### 11.0.2 - 03/14/2023

- Service page edits. All service pages have top 3 cities, instead of company-city.
- Added BxB Loan Calculator to the Financing page. Hidden by default.

### 11.0.1 - 03/07/2023

- Added Electrical pages, set as draft. Categories, Service Items and review system are set up. Gravity form called  `BxB Review System HVAC, PLUMBING and ELECTRICAL`  was added and toggled off. Review row has the class `bxb-reviews-row`

### 11.0.0 - 03/02/2023

- Added CSS to Layout 11 for BxB Review Row.  Display:none when there are no reviews.

```css
.bxb-reviews-row:not(:has(.ptb_testimonial_row)) {
display: none;
}
```

- Added class name of `.bxb-reviews-row` to all service page review rows, as well as Service page template
- Added all plumbing/water treatment service pages, and plumbing city content. Plumbing City content only has 15 versions. Added all plumbing service items and categories, and set up meta fields for plumbing in Gravity form.
- Added field to Company Info options for button color for the Connect GF email notification.
- Added field to Company Info Options for url for the connect form to be used in the Connect GF confirmation. This was being overlooked and left with the default url.

      (ie. `https://domain.com/connect/`)

- Corrections made on city pages (various typos corrected)
- Added custom field to Pages post type called “Page Type” and categorized the pages to
    - Residential HVAC
    - Residential Plumbing/Water Treatment
    - Commercial
    - Landing Page
    - Products
    - Electrical
- Confirmation (thank you) pages set to height 400px
- Added comments to snippet called BxB Custom Shortcodes (Using Options Settings)
- Added **Reverse Osmosis** Service page (still needs banner)
- Added **Sewer Camera Inspection** to city page content