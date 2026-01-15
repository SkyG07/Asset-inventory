/**
 * Reusable function to generate PDF from any table.
 * tableId: id of the table element
 * title: main title to show on top
 * filename: PDF filename
 */
function generatePDF(
  tableId = "logsTable",
  title = "Report",
  filename = "Report.pdf"
) {
  if (!window.jspdf) {
    console.error("jsPDF is not loaded!");
    return;
  }

  const { jsPDF } = window.jspdf;
  const doc = new jsPDF("p", "pt", "a4");

  // Title
  doc.setFontSize(14);
  doc.text("CITY GOVERNMENT OF GAPAN", 40, 40);
  doc.setFontSize(12);
  doc.text(title, 40, 60);

  // Find the table
  const table = document.getElementById(tableId);
  if (!table) {
    console.error(`Table with ID "${tableId}" not found!`);
    return;
  }

  // Read headers dynamically
  const thead = table.querySelectorAll("thead tr th");
  const headers = Array.from(thead).map((th) => th.innerText);

  // Read table body
  const rows = [];
  table.querySelectorAll("tbody tr").forEach((tr) => {
    const row = Array.from(tr.querySelectorAll("td")).map((td) => td.innerText);
    rows.push(row);
  });

  // Generate PDF table
  doc.autoTable({
    startY: 80,
    head: [headers],
    body: rows,
    styles: { fontSize: 10 },
    headStyles: { fillColor: [52, 58, 64], textColor: 255 },
    theme: "grid",
    margin: { left: 40, right: 40 },
    pageBreak: "auto",
  });

  doc.save(filename);
}
