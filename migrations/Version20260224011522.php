<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260224011522 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie (id_category INT AUTO_INCREMENT NOT NULL, nom_category VARCHAR(255) NOT NULL, PRIMARY KEY (id_category)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE cmd_extern (id_cmd_ext INT AUTO_INCREMENT NOT NULL, date_ce DATE NOT NULL, statut ENUM(\'Brouillon\', \'Commandée\', \'Expédiée\', \'Réceptionnée Partiellement\', \'Réceptionnée\', \'Annulée\'), idFournisseur INT DEFAULT NULL, INDEX IDX_7993DA2576C3354A (idFournisseur), PRIMARY KEY (id_cmd_ext)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE cmd_intern (id_cmd_int INT AUTO_INCREMENT NOT NULL, date_ci DATE NOT NULL, statut ENUM(\'Prête\', \'En cours de préparation\', \'Rejetée\', \'Approuvée\', \'En attente\', \'Livrée\'), idFonct INT DEFAULT NULL, INDEX IDX_E1F1787CFF20BAD3 (idFonct), PRIMARY KEY (id_cmd_int)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE comite (id_comit INT AUTO_INCREMENT NOT NULL, idFor INT DEFAULT NULL, INDEX IDX_DC01CA9FCE3D147F (idFor), PRIMARY KEY (id_comit)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE fonctionnaire (id_fonct INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, responsable TINYINT NOT NULL, idOrg INT DEFAULT NULL, INDEX IDX_2C72EE1C535DA707 (idOrg), PRIMARY KEY (id_fonct)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE fournisseur (id_fournisseur INT AUTO_INCREMENT NOT NULL, denominateur VARCHAR(255) NOT NULL, contact VARCHAR(255) NOT NULL, adresse LONGTEXT NOT NULL, PRIMARY KEY (id_fournisseur)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE ligne_cmd_extern (num_l_ce INT AUTO_INCREMENT NOT NULL, quantite INT NOT NULL, idCmdExt INT DEFAULT NULL, idProduit INT DEFAULT NULL, INDEX IDX_9A3F34384F8AAE95 (idCmdExt), INDEX IDX_9A3F3438391C87D5 (idProduit), PRIMARY KEY (num_l_ce)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE ligne_cmd_intern (num_l_ci INT AUTO_INCREMENT NOT NULL, quantite INT NOT NULL, idCi INT DEFAULT NULL, idProduit INT DEFAULT NULL, INDEX IDX_25D9661C78EC153 (idCi), INDEX IDX_25D9661391C87D5 (idProduit), PRIMARY KEY (num_l_ci)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE ligne_liv_extern (num_l_le INT AUTO_INCREMENT NOT NULL, qte_livree INT NOT NULL, idLivExt INT DEFAULT NULL, idProduit INT DEFAULT NULL, INDEX IDX_84608EFB14C2594 (idLivExt), INDEX IDX_84608EF391C87D5 (idProduit), PRIMARY KEY (num_l_le)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE ligne_liv_intern (num_l_li INT AUTO_INCREMENT NOT NULL, qte_livree INT NOT NULL, idLivInt INT DEFAULT NULL, idProduit INT DEFAULT NULL, INDEX IDX_9024AAB6A4CE6927 (idLivInt), INDEX IDX_9024AAB6391C87D5 (idProduit), PRIMARY KEY (num_l_li)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE liv_extern (id_liv_ext INT AUTO_INCREMENT NOT NULL, date_le DATE NOT NULL, idCmdExt INT DEFAULT NULL, idLext INT DEFAULT NULL, INDEX IDX_EBEAE6F24F8AAE95 (idCmdExt), INDEX IDX_EBEAE6F2DDDB8629 (idLext), PRIMARY KEY (id_liv_ext)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE liv_intern (id_liv_int INT AUTO_INCREMENT NOT NULL, date_li DATE NOT NULL, idCmdIntern INT DEFAULT NULL, idFonctionnaire INT DEFAULT NULL, INDEX IDX_738844AB580DA4A6 (idCmdIntern), INDEX IDX_738844ABE696B232 (idFonctionnaire), PRIMARY KEY (id_liv_int)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE organigramme (id_org INT AUTO_INCREMENT NOT NULL, departement VARCHAR(255) NOT NULL, PRIMARY KEY (id_org)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE produit (id_produit INT AUTO_INCREMENT NOT NULL, designation VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, qte_stock INT NOT NULL, seuil_alert INT NOT NULL, idUnite INT DEFAULT NULL, idSousCat INT DEFAULT NULL, INDEX IDX_29A5EC27AA08205 (idUnite), INDEX IDX_29A5EC27B81D7F22 (idSousCat), PRIMARY KEY (id_produit)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE sous_categorie (id_sous_cat INT AUTO_INCREMENT NOT NULL, nom_sous_cat VARCHAR(255) NOT NULL, idCategory INT DEFAULT NULL, INDEX IDX_52743D7B55EF339A (idCategory), PRIMARY KEY (id_sous_cat)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE unit (id_unite INT AUTO_INCREMENT NOT NULL, nom_unite VARCHAR(255) NOT NULL, PRIMARY KEY (id_unite)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE cmd_extern ADD CONSTRAINT FK_7993DA2576C3354A FOREIGN KEY (idFournisseur) REFERENCES fournisseur (id_fournisseur)');
        $this->addSql('ALTER TABLE cmd_intern ADD CONSTRAINT FK_E1F1787CFF20BAD3 FOREIGN KEY (idFonct) REFERENCES fonctionnaire (id_fonct)');
        $this->addSql('ALTER TABLE comite ADD CONSTRAINT FK_DC01CA9FCE3D147F FOREIGN KEY (idFor) REFERENCES fournisseur (id_fournisseur)');
        $this->addSql('ALTER TABLE fonctionnaire ADD CONSTRAINT FK_2C72EE1C535DA707 FOREIGN KEY (idOrg) REFERENCES organigramme (id_org)');
        $this->addSql('ALTER TABLE ligne_cmd_extern ADD CONSTRAINT FK_9A3F34384F8AAE95 FOREIGN KEY (idCmdExt) REFERENCES cmd_extern (id_cmd_ext)');
        $this->addSql('ALTER TABLE ligne_cmd_extern ADD CONSTRAINT FK_9A3F3438391C87D5 FOREIGN KEY (idProduit) REFERENCES produit (id_produit)');
        $this->addSql('ALTER TABLE ligne_cmd_intern ADD CONSTRAINT FK_25D9661C78EC153 FOREIGN KEY (idCi) REFERENCES cmd_intern (id_cmd_int)');
        $this->addSql('ALTER TABLE ligne_cmd_intern ADD CONSTRAINT FK_25D9661391C87D5 FOREIGN KEY (idProduit) REFERENCES produit (id_produit)');
        $this->addSql('ALTER TABLE ligne_liv_extern ADD CONSTRAINT FK_84608EFB14C2594 FOREIGN KEY (idLivExt) REFERENCES liv_extern (id_liv_ext)');
        $this->addSql('ALTER TABLE ligne_liv_extern ADD CONSTRAINT FK_84608EF391C87D5 FOREIGN KEY (idProduit) REFERENCES produit (id_produit)');
        $this->addSql('ALTER TABLE ligne_liv_intern ADD CONSTRAINT FK_9024AAB6A4CE6927 FOREIGN KEY (idLivInt) REFERENCES liv_intern (id_liv_int)');
        $this->addSql('ALTER TABLE ligne_liv_intern ADD CONSTRAINT FK_9024AAB6391C87D5 FOREIGN KEY (idProduit) REFERENCES produit (id_produit)');
        $this->addSql('ALTER TABLE liv_extern ADD CONSTRAINT FK_EBEAE6F24F8AAE95 FOREIGN KEY (idCmdExt) REFERENCES cmd_extern (id_cmd_ext)');
        $this->addSql('ALTER TABLE liv_extern ADD CONSTRAINT FK_EBEAE6F2DDDB8629 FOREIGN KEY (idLext) REFERENCES comite (id_comit)');
        $this->addSql('ALTER TABLE liv_intern ADD CONSTRAINT FK_738844AB580DA4A6 FOREIGN KEY (idCmdIntern) REFERENCES cmd_intern (id_cmd_int)');
        $this->addSql('ALTER TABLE liv_intern ADD CONSTRAINT FK_738844ABE696B232 FOREIGN KEY (idFonctionnaire) REFERENCES fonctionnaire (id_fonct)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27AA08205 FOREIGN KEY (idUnite) REFERENCES unit (id_unite)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27B81D7F22 FOREIGN KEY (idSousCat) REFERENCES sous_categorie (id_sous_cat)');
        $this->addSql('ALTER TABLE sous_categorie ADD CONSTRAINT FK_52743D7B55EF339A FOREIGN KEY (idCategory) REFERENCES categorie (id_category)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cmd_extern DROP FOREIGN KEY FK_7993DA2576C3354A');
        $this->addSql('ALTER TABLE cmd_intern DROP FOREIGN KEY FK_E1F1787CFF20BAD3');
        $this->addSql('ALTER TABLE comite DROP FOREIGN KEY FK_DC01CA9FCE3D147F');
        $this->addSql('ALTER TABLE fonctionnaire DROP FOREIGN KEY FK_2C72EE1C535DA707');
        $this->addSql('ALTER TABLE ligne_cmd_extern DROP FOREIGN KEY FK_9A3F34384F8AAE95');
        $this->addSql('ALTER TABLE ligne_cmd_extern DROP FOREIGN KEY FK_9A3F3438391C87D5');
        $this->addSql('ALTER TABLE ligne_cmd_intern DROP FOREIGN KEY FK_25D9661C78EC153');
        $this->addSql('ALTER TABLE ligne_cmd_intern DROP FOREIGN KEY FK_25D9661391C87D5');
        $this->addSql('ALTER TABLE ligne_liv_extern DROP FOREIGN KEY FK_84608EFB14C2594');
        $this->addSql('ALTER TABLE ligne_liv_extern DROP FOREIGN KEY FK_84608EF391C87D5');
        $this->addSql('ALTER TABLE ligne_liv_intern DROP FOREIGN KEY FK_9024AAB6A4CE6927');
        $this->addSql('ALTER TABLE ligne_liv_intern DROP FOREIGN KEY FK_9024AAB6391C87D5');
        $this->addSql('ALTER TABLE liv_extern DROP FOREIGN KEY FK_EBEAE6F24F8AAE95');
        $this->addSql('ALTER TABLE liv_extern DROP FOREIGN KEY FK_EBEAE6F2DDDB8629');
        $this->addSql('ALTER TABLE liv_intern DROP FOREIGN KEY FK_738844AB580DA4A6');
        $this->addSql('ALTER TABLE liv_intern DROP FOREIGN KEY FK_738844ABE696B232');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27AA08205');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27B81D7F22');
        $this->addSql('ALTER TABLE sous_categorie DROP FOREIGN KEY FK_52743D7B55EF339A');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE cmd_extern');
        $this->addSql('DROP TABLE cmd_intern');
        $this->addSql('DROP TABLE comite');
        $this->addSql('DROP TABLE fonctionnaire');
        $this->addSql('DROP TABLE fournisseur');
        $this->addSql('DROP TABLE ligne_cmd_extern');
        $this->addSql('DROP TABLE ligne_cmd_intern');
        $this->addSql('DROP TABLE ligne_liv_extern');
        $this->addSql('DROP TABLE ligne_liv_intern');
        $this->addSql('DROP TABLE liv_extern');
        $this->addSql('DROP TABLE liv_intern');
        $this->addSql('DROP TABLE organigramme');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE sous_categorie');
        $this->addSql('DROP TABLE unit');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
