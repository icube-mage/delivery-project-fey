DELIMITER $$

DROP TRIGGER /*!50032 IF EXISTS */ `countAverages`$$

CREATE
    TRIGGER `countAverages` AFTER INSERT ON `catalog_prices` 
    FOR EACH ROW BEGIN
	SET @sku = NEW.sku;
	SET @brand = NEW.brand;
	SET @marketplace = NEW.marketplace;
	SET @warehouse = NEW.warehouse;
	SET @is_whitelist = NEW.is_whitelist;
	SET @is_discount = NEW.is_discount;
	IF @is_whitelist = 0  THEN
		IF @is_discount = 1  THEN
			IF @warehouse IS NULL THEN
				SET @avgPriceCat = (SELECT average_price FROM catalog_price_averages WHERE sku=@sku AND brand=@brand AND marketplace=@marketplace AND warehouse IS NULL);
				SET @totalDataPrice = (SELECT COUNT(*) AS `count` FROM catalog_prices WHERE sku=@sku AND brand=@brand AND marketplace=@marketplace AND warehouse IS NULL AND is_discount=1 AND is_whitelist=0);
				SET @countPriceTemp = (SELECT COUNT(*) AS `count` FROM catalog_price_temps WHERE sku=@sku AND brand=@brand AND marketplace=@marketplace AND warehouse IS NULL AND is_discount=1 AND is_whitelist=0);
				SET @totalPriceTemp = (SELECT COALESCE(SUM(discount_price),0) AS `sum` FROM catalog_price_temps WHERE sku=@sku AND brand=@brand AND marketplace=@marketplace AND warehouse IS NULL AND is_discount=1 AND is_whitelist=0);
				SET @countNewAvg = (((@avgPriceCat * @totalDataPrice) + @totalPriceTemp) / (@totalDataPrice + @countPriceTemp));
				SET @newTotalRecord = (@totalDataPrice+@countPriceTemp);
				UPDATE catalog_price_averages SET average_price=@countNewAvg, total_record=@newTotalRecord WHERE sku=@sku AND brand=@brand AND marketplace=@marketplace AND warehouse IS NULL;
			ELSE
				SET @avgPriceCat = (SELECT average_price FROM catalog_price_averages WHERE sku=@sku AND brand=@brand AND marketplace=@marketplace AND warehouse=@warehouse);
				SET @totalDataPrice = (SELECT COUNT(*) AS `count` FROM catalog_prices WHERE sku=@sku AND brand=@brand AND marketplace=@marketplace AND warehouse=@warehouse AND is_discount=1 AND is_whitelist=0);
				SET @countPriceTemp = (SELECT COUNT(*) AS `count` FROM catalog_price_temps WHERE sku=@sku AND brand=@brand AND marketplace=@marketplace AND warehouse=@warehouse AND is_discount=1 AND is_whitelist=0);
				SET @totalPriceTemp = (SELECT COALESCE(SUM(discount_price),0) AS `sum` FROM catalog_price_temps WHERE sku=@sku AND brand=@brand AND marketplace=@marketplace AND warehouse=@warehouse AND is_discount=1 AND is_whitelist=0);
				SET @countNewAvg = (((@avgPriceCat * @totalDataPrice) + @totalPriceTemp) / (@totalDataPrice + @countPriceTemp));
				
				SET @newTotalRecord = (@totalDataPrice+@countPriceTemp);
				UPDATE catalog_price_averages SET average_price=@countNewAvg, total_record=@newTotalRecord WHERE sku=@sku AND brand=@brand AND marketplace=@marketplace AND warehouse=@warehouse;
			END IF;
			INSERT INTO temp (`sku`, `data1`, `data2`, `data3`, `average`, `text`) VALUES(@sku, @brand, @marketplace, @warehouse, @newTotalRecord, @countNewAvg);
		END IF;
	END IF;
END;
$$

DELIMITER ;