const fs = require("fs");
const path = require("path");
const { execSync } = require("child_process");

// Supprimer l'ancien zip s'il existe
const zipName = "theme.zip";
if (fs.existsSync(zipName)) {
  fs.unlinkSync(zipName);
  console.log(`Ancien ${zipName} supprimé`);
}

// Liste des patterns à exclure
const excludePatterns = ["*.zip", "node_modules", ".git", ".sass-cache", ".vscode", ".claude", "create-zip.js"];

// Fonction pour vérifier si un fichier doit être exclu
function shouldExclude(filePath) {
  return excludePatterns.some((pattern) => {
    if (pattern.startsWith("*.")) {
      // Pattern d'extension
      return filePath.endsWith(pattern.substring(1));
    } else {
      // Pattern de dossier ou fichier
      return filePath.includes(path.sep + pattern) || filePath.startsWith(pattern + path.sep) || filePath === pattern;
    }
  });
}

// Récupérer tous les fichiers récursivement
function getAllFiles(dirPath, arrayOfFiles = []) {
  const files = fs.readdirSync(dirPath);

  files.forEach((file) => {
    const fullPath = path.join(dirPath, file);
    const relativePath = path.relative(".", fullPath);

    if (shouldExclude(relativePath)) {
      return;
    }

    if (fs.statSync(fullPath).isDirectory()) {
      arrayOfFiles = getAllFiles(fullPath, arrayOfFiles);
    } else {
      arrayOfFiles.push(relativePath);
    }
  });

  return arrayOfFiles;
}

// Obtenir la liste des fichiers à zipper
const filesToZip = getAllFiles(".");

console.log(`Création de ${zipName} avec ${filesToZip.length} fichiers...`);

// Créer le zip avec bestzip
try {
  const command = `npx bestzip ${zipName} ${filesToZip.map((f) => `"${f}"`).join(" ")}`;
  execSync(command, { stdio: "inherit" });
  console.log(`✓ Archive créée : ${zipName}`);
} catch (error) {
  console.error("Erreur lors de la création du zip:", error.message);
  process.exit(1);
}
