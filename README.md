# Full Stack Test
WPoets Full Stack Developer Test

**Submitted by:** Quazi Mehmoodul Hasan

Hi Full-stacker!

Great that you're interested in this exercise! Thanks a lot for making it. The exercise consits of an assignment. It is related to the WPoets working ways. Good luck and we are looking forward to hearing from you soon!

To complete these assignment you need to fork this repo. When you're done you can push your changes to your own repo (and let us know where to find it ofcourse).

---

## Solution — how to run

This solution implements the "DelphianLogic in Action" section with full CRUD
(PHP + MySQL), styled to match the design, using HTML5/CSS3/jQuery/Bootstrap 4 and
the Slick carousel. Everything is bundled in Docker so it runs with one command.

### Requirements
- Docker + Docker Compose

### Run
```bash
docker compose up --build
```
Wait until MySQL has initialised (first boot seeds the schema + demo data), then open:

| What | URL |
|------|-----|
| **Front-end section** | http://localhost:8080/public/ |
| **Admin (CRUD)** | http://localhost:8080/admin/ |

The database schema (`db/schema.sql`) and seed data (`db/seed.sql`) are loaded
automatically on first boot.

### Admin login (demo)
The admin uses a minimal shared-password gate (a demo, not real user accounts).
Default credentials — **also shown on the login page**:

```
Username: admin
Password: admin
```
Override via the `ADMIN_USER` / `ADMIN_PASS` environment variables in
`docker-compose.yml`.

### How it maps to the brief
- **Column 1** tabs (Learning / Technology / Communication) — each tab is its own
  slider. On mobile it becomes a +/− accordion.
- **Column 2** content slider carries the controls (dots) and is synced to **Column 3**
  via Slick's `asNavFor`, so the 1:1 image changes with the slide. On mobile, Column 3's
  images become the slider's background images.
- **CRUD** for both tabs and slides (with image upload) lives in `/admin`.
- Colours, fonts, radii and spacing come from `files/Moodboard.xlsx` and are encoded as
  CSS custom properties in `public/assets/css/style.css`.

> **Note on assets:** Bootstrap, jQuery and Slick load from CDN
> so the first run needs internet access.

> **Note on ports:** the app is published on host port **8080** and MySQL on **3307**
> (to avoid clashing with a local MySQL). Adjust in `docker-compose.yml` if needed.

---

<h2>Task</h2>
<ul>
  <li>Create a CRUD functionality using PHP, MySQL.</li>
	<li>Fetch the data to display the section that matches the given design using HTML5, CSS3, jQuery, Bootstrap.</li>
</ul>

<h2>Design</h2>

<h5>In Web view</h5>
<ul>
  <li>Column 1 is tabs. Each tab is a seperate slider.</li>
	<li>Clicking on the tab will change the slider in Column 2.</li>
	<li>
		Column 2 is a slider connected with column 3.
		<ul>
			<li>Which means when the slide in column 2 changes, the image in column 3 will change with it.</li>
			<li>Controls are attached to column 2 only.</li>
		</ul>
	</li>
	<li>Image in column 3 is a 1:1 image.</li>
</ul>

<h5>In Mobile view</h5>
<ul>
  <li>Column 1 changes to accordion.</li>
  <li>Column 2 is a slider with images from column 3 as background images.</li>
</ul>

<strong>Note: Please refer to the files directory for design files, relevant icons/images and styleguide.</strong>

<h2>Technical questions</h2>

Please answer the following questions in a markdown file called <code>Answers to technical questions.md</code>
<ul>
  <li>How long did you spend on the coding test? What would you add to your solution if you had more time? If you didn't spend much time on the coding test then use this as an opportunity to explain what you would add.</li>
	<li>How would you track down a performance issue in production? Have you ever had to do this?</li>
	<li>Please describe yourself using JSON.</li>
</ul>