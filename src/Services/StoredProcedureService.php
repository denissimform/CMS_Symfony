<?php

namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;

class StoredProcedureService
{

    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function callProcedure()
    {
        $connection = $this->entityManager->getConnection();

        $sql = 'CREATE PROCEDURE IF NOT EXISTS `chartData` (IN companyID INT)
        BEGIN
            
            SELECT count(*) INTO @companyActive FROM company WHERE company.is_active = 1;
            SELECT count(*) INTO @companyInactive FROM company WHERE company.is_active = 0;
            
            SET @query1 = CONCAT(\'SELECT count(*) INTO @cntBda FROM user WHERE user.roles->"$[0]" = "ROLE_BDA"\');
            SET @query2 = CONCAT(\'SELECT count(*) INTO @cntAdmin FROM user WHERE user.roles->"$[0]" = "ROLE_ADMIN"\');
            SET @query3 = CONCAT(\'SELECT count(*) INTO @total FROM user\');
            SET @growthQuery = CONCAT(\'SELECT count(*) as Count, YEAR(created_at) as Year FROM user GROUP BY year(created_at) ORDER BY Year ASC;\');
            SET @subscriptionQuery = CONCAT(\'SELECT count(*) as Count, s.type FROM subscription_duration sd INNER JOIN subscription s ON sd.subscription_id_id = s.id GROUP BY s.id;\');
            
            IF companyID != -1 THEN
                    SET @query1 = CONCAT(@query1, \' AND user.company_id = \', companyID, \';\');
                    SET @query2 = CONCAT(@query2, \' AND user.company_id = \', companyID, \';\');
                    SET @query3 = CONCAT(@query3, \' WHERE user.company_id = \', companyID, \';\');
                    SET @growthQuery = CONCAT(\'SELECT count(*) as Count, YEAR(created_at) as Year FROM user WHERE user.company_id = \', companyID, \' GROUP BY year(created_at) ORDER BY Year ASC\');
            END IF;
            
            PREPARE STMT1 FROM @query1;
            PREPARE STMT2 FROM @query2;
            PREPARE STMT3 FROM @query3;
            PREPARE STMT4 FROM @growthQuery;
            PREPARE STMT5 FROM @subscriptionQuery;
            
            EXECUTE STMT1;
            EXECUTE STMT2;
            EXECUTE STMT3;
            EXECUTE STMT4;
            EXECUTE STMT5;
            
            SELECT @companyActive, @companyInactive, @cntAdmin, @cntBda, @total;
        END';

        $connection->executeQuery($sql);
    }
}
