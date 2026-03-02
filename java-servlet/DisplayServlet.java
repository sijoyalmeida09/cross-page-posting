import java.io.*;
import javax.servlet.*;
import javax.servlet.http.*;
import javax.servlet.annotation.WebServlet;

/**
 * DisplayServlet.java — Page B in our Cross Page Posting demo
 *
 * Think of this as the "receiving end": when a student fills out the form on
 * student.html (Page A) and hits Submit, the browser sends that data here.
 * We read it, optionally validate it, and send back a nice HTML summary.
 *
 * Flow:  student.html  ──[POST]──►  DisplayServlet  ──►  HTML response
 *        (Page A)                   (this class)         (what the user sees)
 *
 * In short:
 *   - HttpServletRequest  : holds whatever the form sent (rollno, course, etc.)
 *   - getParameter("name"): grabs a form field by its name attribute
 *   - HttpServletResponse : we write HTML into this and it goes to the browser
 *
 * @author  [Your Name]
 * @course  [Course Name]
 * @version 1.0
 */
@WebServlet("/DisplayServlet")
public class DisplayServlet extends HttpServlet {

    /**
     * doPost() — where the magic happens.
     * When the user submits the form on student.html, the browser sends a POST
     * request to this servlet. We pull out the form fields and build the
     * "thank you" page they see.
     */
    @Override
    protected void doPost(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {

        // Pull out whatever the form sent — same names as the input "name" attributes
        String rollNo = request.getParameter("rollno");
        String course = request.getParameter("course");

        // If someone left a field blank or sent junk, show something friendly instead of "null"
        if (rollNo == null || rollNo.trim().isEmpty()) rollNo = "Not provided";
        if (course  == null || course.trim().isEmpty())  course  = "Not provided";

        // Tell the browser we're sending back HTML (and in UTF-8 so special chars work)
        response.setContentType("text/html;charset=UTF-8");

        // Get a writer and build the response page line by line
        PrintWriter out = response.getWriter();

        out.println("<!DOCTYPE html>");
        out.println("<html lang='en'>");
        out.println("<head>");
        out.println("  <meta charset='UTF-8'>");
        out.println("  <meta name='viewport' content='width=device-width, initial-scale=1.0'>");
        out.println("  <title>Student Details — Cross Page Posting Result</title>");
        out.println("  <style>");
        out.println("    * { margin:0; padding:0; box-sizing:border-box; }");
        out.println("    body { font-family: 'Segoe UI', Arial, sans-serif;");
        out.println("           background:#f5f5f5; display:flex;");
        out.println("           flex-direction:column; align-items:center;");
        out.println("           justify-content:center; min-height:100vh;");
        out.println("           padding:20px; }");
        out.println("    .card { background:#fff; border-radius:10px;");
        out.println("            box-shadow:0 4px 20px rgba(0,0,0,0.1);");
        out.println("            padding:40px 48px; max-width:480px; width:100%; }");
        out.println("    .card::before { content:''; display:block; height:4px;");
        out.println("                   background:linear-gradient(90deg,#4CAF50,#81C784);");
        out.println("                   border-radius:4px 4px 0 0; margin:-40px -48px 30px; }");
        out.println("    h2 { font-size:24px; color:#2e7d32; margin-bottom:4px; }");
        out.println("    .sub { font-size:13px; color:#888; margin-bottom:28px; }");
        out.println("    table { width:100%; border-collapse:collapse; }");
        out.println("    tr { border-bottom:1px solid #f0f0f0; }");
        out.println("    tr:last-child { border:none; }");
        out.println("    td { padding:14px 8px; font-size:14px; }");
        out.println("    td:first-child { color:#555; font-weight:600; width:44%; }");
        out.println("    td:last-child { color:#222; font-family:monospace; font-size:15px; }");
        out.println("    .badge { display:inline-block; padding:3px 12px;");
        out.println("             border-radius:12px; font-size:12px; font-weight:600; }");
        out.println("    .green { background:#e8f5e9; color:#2e7d32; }");
        out.println("    .blue  { background:#e3f2fd; color:#1565c0; }");
        out.println("    .back { display:block; text-align:center; margin-top:28px;");
        out.println("            color:#4CAF50; text-decoration:none; font-size:14px; }");
        out.println("    .back:hover { text-decoration:underline; }");
        out.println("  </style>");
        out.println("</head>");
        out.println("<body>");
        // Card layout: title, table of received data, and a link to go back and try again
        out.println("  <div class='card'>");
        out.println("    <h2>&#x1F4CB; Student Details</h2>");
        out.println("    <div class='sub'>Received via Cross Page Posting from student.html</div>");
        out.println("    <table>");
        out.println("      <tr>");
        out.println("        <td>Roll Number</td>");
        out.println("        <td>" + escapeHtml(rollNo) + "</td>");
        out.println("      </tr>");
        out.println("      <tr>");
        out.println("        <td>Course Name</td>");
        out.println("        <td>" + escapeHtml(course) + "</td>");
        out.println("      </tr>");
        out.println("      <tr>");
        out.println("        <td>HTTP Method</td>");
        out.println("        <td><span class='badge green'>POST</span></td>");
        out.println("      </tr>");
        out.println("      <tr>");
        out.println("        <td>Source Page</td>");
        out.println("        <td><span class='badge blue'>student.html</span></td>");
        out.println("      </tr>");
        out.println("      <tr>");
        out.println("        <td>Handler Servlet</td>");
        out.println("        <td>DisplayServlet.java</td>");
        out.println("      </tr>");
        out.println("    </table>");
        out.println("    <a href='student.html' class='back'>← Back to Form</a>");
        out.println("  </div>");
        out.println("</body>");
        out.println("</html>");

        out.close();
    }

    /**
     * doGet() — if someone visits /DisplayServlet directly in the address bar,
     * we don't have any form data to show. So we just send them back to the
     * form page. Keeps the flow sensible.
     */
    @Override
    protected void doGet(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        response.sendRedirect("student.html");
    }

    /**
     * escapeHtml() — safety first.
     * User input could contain <script> or other HTML. We escape special
     * characters so it shows as plain text instead of running or breaking the page.
     */
    private String escapeHtml(String input) {
        if (input == null) return "";
        return input
            .replace("&", "&amp;")
            .replace("<", "&lt;")
            .replace(">", "&gt;")
            .replace("\"", "&quot;")
            .replace("'", "&#x27;");
    }
}