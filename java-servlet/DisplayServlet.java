import java.io.IOException;
import java.io.PrintWriter;
import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

@WebServlet("/DisplayServlet")
public class DisplayServlet extends HttpServlet {
    private static final long serialVersionUID = 1L;

    protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
        response.setContentType("text/html");
        PrintWriter out = response.getWriter();

        String name = request.getParameter("name");
        String email = request.getParameter("email");
        String studentId = request.getParameter("studentId");
        String major = request.getParameter("major");
        String gpa = request.getParameter("gpa");

        out.println("<!DOCTYPE html>");
        out.println("<html lang=\"en\">");
        out.println("<head>");
        out.println("    <meta charset=\"UTF-8\">");
        out.println("    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">");
        out.println("    <title>Submission Details (Servlet)</title>");
        out.println("    <style>");
        out.println("        body {\n";
        out.println("            font-family: Arial, sans-serif;\n";
        out.println("            background-color: #1a1a1a;\n";
        out.println("            color: #f0f0f0;\n";
        out.println("            display: flex;\n";
        out.println("            justify-content: center;\n";
        out.println("            align-items: center;\n";
        out.println("            min-height: 100vh;\n";
        out.println("            margin: 0;\n";
        out.println("        }\n";
        out.println("        .container {\n";
        out.println("            background-color: #2a2a2a;\n";
        out.println("            padding: 30px;\n";
        out.println("            border-radius: 8px;\n";
        out.println("            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);\n";
        out.println("            width: 100%;\n";
        out.println("            max-width: 400px;\n";
        out.println("        }\n";
        out.println("        h2 {\n";
        out.println("            color: #4CAF50;\n";
        out.println("            text-align: center;\n";
        out.println("            margin-bottom: 20px;\n";
        out.println("        }\n";
        out.println("        p {\n";
        out.println("            margin-bottom: 10px;\n";
        out.println("        }\n";
        out.println("        strong {\n";
        out.println("            color: #4CAF50;\n";
        out.println("        }\n";
        out.println("    </style>\n");
        out.println("</head>");
        out.println("<body>");
        out.println("    <div class=\"container\">");
        out.println("        <h2>Submission Details</h2>");
        out.println("        <p><strong>Name:</strong> " + name + "</p>");
        out.println("        <p><strong>Email:</strong> " + email + "</p>");
        out.println("        <p><strong>Student ID:</strong> " + studentId + "</p>");
        out.println("        <p><strong>Major:</strong> " + (major != null && !major.isEmpty() ? major : "N/A") + "</p>");
        out.println("        <p><strong>GPA:</strong> " + (gpa != null && !gpa.isEmpty() ? gpa : "N/A") + "</p>");
        out.println("    </div>");
        out.println("</body>");
        out.println("</html>");
    }
}
