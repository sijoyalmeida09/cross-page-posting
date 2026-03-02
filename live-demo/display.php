<?php
/**
 * display.php — Page B in the Cross Page Posting demo
 *
 * This is the PHP counterpart to DisplayServlet.java. When the user submits
 * the form on index.html, the browser POSTs here. We read the fields (same
 * idea as request.getParameter() in Java), sanitize them, and render the
 * "thank you" page with the data they sent.
 *
 * Flow:  index.html  ──[POST]──►  display.php  ──►  HTML result page
 */

// If someone lands here via GET (e.g. typed the URL), send them back to the form
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.html');
    exit;
}

// Grab what the form sent and escape it so we don't output raw HTML (XSS safety)
$rollNo = htmlspecialchars(trim($_POST['rollno'] ?? ''), ENT_QUOTES, 'UTF-8');
$course  = htmlspecialchars(trim($_POST['course']  ?? ''), ENT_QUOTES, 'UTF-8');

// Missing data? Redirect back to the form instead of showing a half-empty page
if (empty($rollNo) || empty($course)) {
    header('Location: index.html');
    exit;
}

// Handy for the footer and "when did we receive this" display
$timestamp = date('d M Y, H:i:s');
?>
<!DOCTYPE html>
<html lang="en">
<!-- Output: the "result" page — shows what we received from the form on index.html -->
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Details — Cross Page Posting Result</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=DM+Mono:wght@400;500&family=Instrument+Sans:wght@400;500;600&display=swap" rel="stylesheet">
  <style>
    /* Reset and design tokens (match index.html for consistent look) */
    *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

    :root {
      --bg: #faf8f4;
      --ink: #1a1814;
      --ink-dim: #6b6560;
      --ink-faint: #c4bfb8;
      --accent: #c8522a;
      --accent-light: #f5ede8;
      --border: #e8e2da;
      --surface: #ffffff;
      --green: #2d7d4f;
      --green-light: #edf7f2;
      --serif: 'Instrument Serif', Georgia, serif;
      --mono: 'DM Mono', monospace;
      --sans: 'Instrument Sans', sans-serif;
    }

    html { height: 100%; }

    body {
      background: var(--bg);
      color: var(--ink);
      font-family: var(--sans);
      min-height: 100vh;
      display: grid;
      grid-template-rows: auto 1fr auto;
      position: relative;
    }

    body::before {
      content: '';
      position: fixed;
      inset: 0;
      background-image:
        radial-gradient(circle at 80% 30%, rgba(45,125,79,0.05) 0%, transparent 50%),
        radial-gradient(circle at 20% 70%, rgba(200,82,42,0.03) 0%, transparent 40%);
      pointer-events: none;
    }

    body::after {
      content: '';
      position: fixed;
      inset: 0;
      background-image: linear-gradient(rgba(45,125,79,0.03) 1px, transparent 1px);
      background-size: 100% 32px;
      pointer-events: none;
    }

    header {
      padding: 24px 40px;
      border-bottom: 1px solid var(--border);
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: relative;
      z-index: 10;
    }

    .logo {
      font-family: var(--mono);
      font-size: 12px;
      color: var(--ink-dim);
      letter-spacing: 1px;
    }
    .logo span { color: var(--accent); }

    .header-status {
      display: flex;
      align-items: center;
      gap: 8px;
      font-family: var(--mono);
      font-size: 10px;
      color: var(--green);
      background: var(--green-light);
      padding: 6px 12px;
      border-radius: 20px;
      border: 1px solid rgba(45,125,79,0.15);
    }

    .status-dot {
      width: 6px; height: 6px;
      background: var(--green);
      border-radius: 50%;
      animation: pulse 2s infinite;
    }

    @keyframes pulse {
      0%, 100% { opacity: 1; box-shadow: 0 0 0 0 rgba(45,125,79,0.4); }
      50% { opacity: 0.8; box-shadow: 0 0 0 4px rgba(45,125,79,0); }
    }

    main {
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 60px 24px;
      position: relative;
      z-index: 10;
    }

    .card {
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 4px;
      width: 100%;
      max-width: 560px;
      overflow: hidden;
      box-shadow:
        0 1px 3px rgba(0,0,0,0.06),
        0 8px 32px rgba(0,0,0,0.05),
        0 32px 64px rgba(0,0,0,0.04);
      animation: rise 0.6s cubic-bezier(0.16,1,0.3,1);
    }

    @keyframes rise {
      from { opacity: 0; transform: translateY(20px) scale(0.99); }
      to { opacity: 1; transform: translateY(0) scale(1); }
    }

    .card-header {
      padding: 32px 36px 24px;
      border-bottom: 1px solid var(--border);
      position: relative;
    }

    .card-header::before {
      content: '';
      position: absolute;
      top: 0; left: 0; right: 0;
      height: 3px;
      background: linear-gradient(90deg, var(--green), #5aad7a);
    }

    .page-label {
      font-family: var(--mono);
      font-size: 9px;
      color: var(--green);
      letter-spacing: 3px;
      text-transform: uppercase;
      margin-bottom: 12px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .page-label::after {
      content: '';
      flex: 1;
      height: 1px;
      background: var(--border);
    }

    .card-title {
      font-family: var(--serif);
      font-size: 32px;
      line-height: 1.15;
      margin-bottom: 8px;
    }

    .card-title em { font-style: italic; color: var(--green); }

    .received-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 8px 14px;
      background: var(--green-light);
      border: 1px solid rgba(45,125,79,0.15);
      border-radius: 3px;
      font-family: var(--mono);
      font-size: 11px;
      color: var(--green);
      margin-top: 12px;
    }

    /* DATA TABLE */
    .card-body { padding: 28px 36px; }

    .data-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 24px;
    }

    .data-table tr {
      border-bottom: 1px solid var(--border);
      animation: slideIn 0.4s cubic-bezier(0.16,1,0.3,1) both;
    }

    .data-table tr:nth-child(1) { animation-delay: 0.1s; }
    .data-table tr:nth-child(2) { animation-delay: 0.18s; }
    .data-table tr:nth-child(3) { animation-delay: 0.26s; }
    .data-table tr:nth-child(4) { animation-delay: 0.34s; }
    .data-table tr:nth-child(5) { animation-delay: 0.42s; }
    .data-table tr:last-child { border-bottom: none; }

    @keyframes slideIn {
      from { opacity: 0; transform: translateX(12px); }
      to { opacity: 1; transform: translateX(0); }
    }

    .data-table td {
      padding: 16px 0;
      vertical-align: middle;
    }

    .td-key {
      font-family: var(--mono);
      font-size: 10px;
      color: var(--ink-dim);
      text-transform: uppercase;
      letter-spacing: 1.5px;
      width: 40%;
    }

    .td-val {
      font-family: var(--mono);
      font-size: 14px;
      font-weight: 500;
      text-align: right;
    }

    .td-val.highlight {
      color: var(--accent);
      font-size: 16px;
      font-weight: 500;
    }

    .td-val .tag {
      display: inline-block;
      padding: 3px 10px;
      border-radius: 3px;
      font-size: 11px;
    }

    /* Small badges for HTTP method, source page, handler name */
    .tag-green { background: var(--green-light); color: var(--green); }
    .tag-orange { background: var(--accent-light); color: var(--accent); }
    .tag-gray { background: #f0ece6; color: var(--ink-dim); }

    /* Code snippet showing Java vs PHP equivalence for reading form data */
    .code-block {
      background: #1a1814;
      border-radius: 4px;
      padding: 20px 22px;
      margin-bottom: 20px;
      animation: rise 0.5s 0.5s cubic-bezier(0.16,1,0.3,1) both;
    }

    .code-top {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 14px;
    }

    .code-dots { display: flex; gap: 6px; }
    .code-dot { width: 10px; height: 10px; border-radius: 50%; }
    .cd-r { background: #ff5f57; }
    .cd-y { background: #ffbd2e; }
    .cd-g { background: #28c840; }

    .code-lang {
      font-family: var(--mono);
      font-size: 9px;
      color: #666;
      letter-spacing: 2px;
      text-transform: uppercase;
    }

    .code-body {
      font-family: var(--mono);
      font-size: 12px;
      line-height: 1.8;
      color: #a8a29c;
    }

    .c-comment { color: #555; }
    .c-key { color: #7dd3a8; }
    .c-val { color: #f59e6a; }
    .c-str { color: #a78bfa; }
    .c-fn { color: #60a5fa; }

    /* BACK LINK */
    .back-link {
      display: flex;
      align-items: center;
      gap: 8px;
      font-family: var(--mono);
      font-size: 12px;
      color: var(--ink-dim);
      text-decoration: none;
      padding: 12px 0;
      border-top: 1px solid var(--border);
      transition: color 0.2s;
      animation: rise 0.4s 0.6s both;
    }

    .back-link:hover { color: var(--accent); }

    footer {
      padding: 20px 40px;
      border-top: 1px solid var(--border);
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: relative;
      z-index: 10;
    }

    .footer-left {
      font-family: var(--mono);
      font-size: 10px;
      color: var(--ink-faint);
    }
    .footer-left a { color: var(--accent); text-decoration: none; }

    .footer-right {
      font-family: var(--mono);
      font-size: 10px;
      color: var(--ink-faint);
    }
  </style>
</head>
<body>

  <header>
    <div class="logo">JOSHO<span>IT</span>.COM / cross-page-demo</div>
    <div class="header-status">
      <div class="status-dot"></div>
      POST DATA RECEIVED
    </div>
  </header>

  <main>
    <div class="card">

      <div class="card-header">
        <div class="page-label">Page B — DisplayServlet Response</div>
        <h1 class="card-title">Student<br><em>Details</em></h1>
        <div class="received-badge">
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
          Data received from student.html via Cross Page Posting
        </div>
      </div>

      <div class="card-body">

        <!-- Table of what we received: roll no, course, and a bit of meta info -->
        <table class="data-table">
          <tr>
            <td class="td-key">Roll Number</td>
            <td class="td-val highlight"><?php echo $rollNo; ?></td>
          </tr>
          <tr>
            <td class="td-key">Course Name</td>
            <td class="td-val highlight"><?php echo $course; ?></td>
          </tr>
          <tr>
            <td class="td-key">HTTP Method</td>
            <td class="td-val"><span class="tag tag-orange">POST</span></td>
          </tr>
          <tr>
            <td class="td-key">Source Page</td>
            <td class="td-val"><span class="tag tag-gray">student.html</span></td>
          </tr>
          <tr>
            <td class="td-key">Handler</td>
            <td class="td-val"><span class="tag tag-green">DisplayServlet</span></td>
          </tr>
          <tr>
            <td class="td-key">Timestamp</td>
            <td class="td-val" style="color:var(--ink-dim);font-size:12px;"><?php echo $timestamp; ?></td>
          </tr>
        </table>

        <!-- For folks comparing Java servlets to PHP: same idea, different syntax -->
        <div class="code-block">
          <div class="code-top">
            <div class="code-dots">
              <div class="code-dot cd-r"></div>
              <div class="code-dot cd-y"></div>
              <div class="code-dot cd-g"></div>
            </div>
            <div class="code-lang">Java ↔ PHP Equivalence</div>
          </div>
          <div class="code-body">
            <span class="c-comment">// Java (DisplayServlet.java)</span><br>
            <span class="c-key">String</span> rollNo = request.<span class="c-fn">getParameter</span>(<span class="c-str">"rollno"</span>);<br>
            <span class="c-key">String</span> course = request.<span class="c-fn">getParameter</span>(<span class="c-str">"course"</span>);<br>
            <br>
            <span class="c-comment">// PHP (this file — display.php)</span><br>
            <span class="c-val">$rollNo</span> = <span class="c-fn">htmlspecialchars</span>(<span class="c-val">$_POST</span>[<span class="c-str">"rollno"</span>]);<br>
            <span class="c-val">$course</span>  = <span class="c-fn">htmlspecialchars</span>(<span class="c-val">$_POST</span>[<span class="c-str">"course"</span>]);
          </div>
        </div>

        <!-- Back to the form so they can try again with different data -->
        <a href="index.html" class="back-link">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
          ← Back to student.html (Page A)
        </a>

      </div>
    </div>
  </main>

  <footer>
    <div class="footer-left">
      Hosted on <a href="https://joshoit.com" target="_blank">JoshoIT.com</a> ·
      PHP equivalent of Java's <code>HttpServletRequest.getParameter()</code>
    </div>
    <div class="footer-right"><?php echo $timestamp; ?></div>
  </footer>

</body>
</html>