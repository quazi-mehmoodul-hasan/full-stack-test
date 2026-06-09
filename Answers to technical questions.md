# Answers to Technical Questions

### 1. How long did you spend on the coding test? What would you add if you had more time?

I spent around 3–4 hours on this. I went through the design files
and styleguide carefully before writing any code — I wanted to
understand the layout and data structure fully first. I used Claude
Code as a development assistant to help with scaffolding boilerplate,
running an audit pass, and refactoring repetitive code. All the
architectural decisions, design matching, and logic were directed
and reviewed by me throughout.

If I had more time I would:

- Replace the shared-password gate with real user accounts —
  hashed passwords, login throttling, remember me
- Add drag-and-drop reordering for tabs and slides via AJAX
  instead of the manual sort_order field
- Server-side image resizing on upload to enforce the 1:1 ratio,
  with WebP output for performance
- Vendor Bootstrap, jQuery and Slick locally so it runs
  fully offline without CDN dependency
- A proper CI pipeline with PHPUnit running on every push
  and a lint step (PHP-CS-Fixer / PHPStan)

### 2. How would you track down a performance issue in production? Have you ever had to do this?

Yes. My rule is always measure first, never guess.

I start with the slow query log and EXPLAIN on anything suspicious.
In my experience the database is the problem most of the time —
missing indexes and N+1 queries are the two things I look for
immediately.

I had a situation where a list page was getting noticeably slower
as data grew. The query log showed it was firing a separate query
per row to fetch a related record. One joined query replaced all of
them and response time dropped from ~300ms to under 10ms. Simple
fix once you can actually see what is happening.

For application-level issues I reach for Xdebug or Blackfire to
find hot functions and redundant work. For frontend I check the
Network panel — payload sizes, render-blocking scripts, images
that should be lazy loaded.

The discipline that matters most is changing one thing at a time
and measuring before and after. Otherwise you never really know
what helped.

### 3. Please describe yourself using JSON.

```json
{
  "name": "Quazi Mehmoodul Hasan",
  "role": "Full Stack Developer",
  "based_in": "Indore, India",
  "looking_for": "a good team to build things with",
  "stack": {
    "languages": ["PHP", "JavaScript", "SQL", "HTML", "CSS"],
    "frameworks": ["Laravel", "Vue.js", "React", "Bootstrap", "jQuery"],
    "databases": ["MySQL", "PostgreSQL"],
    "tools": ["Docker", "Git", "Composer", "npm", "Puppeteer", "Claude Code"]
  },
  "how_i_work": [
    "read the design properly before writing a line",
    "keep it simple until complexity is actually needed",
    "prepared statements and escaped output by default",
    "pixel-accurate on layouts",
    "use AI as a development assistant, not a replacement for thinking"
  ],
  "currently_learning": [
    "performance profiling",
    "CI/CD pipelines",
    "AI-augmented development workflows"
  ],
  "coffee": "yes",
  "availability": "open to a conversation"
}
```
