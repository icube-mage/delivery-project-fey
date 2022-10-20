DELIMITER $$

DROP PROCEDURE IF EXISTS `moveTempToPrice`$$

CREATE PROCEDURE `moveTempToPrice`(
	IN `uploadhash` CHAR(36),
	IN `userid` INT,
	IN `brand_name` VARCHAR(255),
	IN `marketplace_name` VARCHAR(255)
)
BEGIN
	INSERT INTO catalog_prices(upload_hash, sku, product_name, retail_price, discount_price, user_id, brand, marketplace, warehouse, is_whitelist, is_negative, is_discount, start_date, created_at, updated_at) 
	SELECT uploadhash AS upload_hash, sku, product_name, retail_price, discount_price, user_id, brand, marketplace, warehouse, is_whitelist, is_negative, is_discount, start_date, created_at, updated_at FROM catalog_price_temps WHERE `user_id`=userid AND `brand`=brand_name AND `marketplace`=marketplace_name;
	
	DELETE FROM catalog_price_temps WHERE `brand`=brand_name AND `marketplace`=marketplace_name AND `user_id`=userid;
END$$

DELIMITER ;