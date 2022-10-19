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
	SET @user_id = NEW.user_id;
	IF @is_whitelist = 0  THEN
		IF @is_discount = 1  THEN
			IF @warehouse IS NULL THEN
				SET @isFirstRecord = (SELECT total_record FROM catalog_price_averages WHERE sku=@sku AND brand=@brand AND marketplace=@marketplace AND warehouse IS NULL);
				SET @countDataPrice = (SELECT COUNT(*) AS `count` FROM catalog_prices WHERE sku=@sku AND brand=@brand AND marketplace=@marketplace AND warehouse IS NULL AND is_discount=1 AND is_whitelist=0);
				IF @isFirstRecord <> @countDataPrice THEN
					
					SET @totalPrice = (SELECT SUM(discount_price) AS `sum` FROM catalog_prices WHERE sku=@sku AND brand=@brand AND marketplace=@marketplace AND warehouse IS NULL AND is_discount=1 AND is_whitelist=0);
					SET @countNewAvg = (@totalPrice / @countDataPrice);
	
					UPDATE catalog_price_averages SET average_price=@countNewAvg, total_record=@countDataPrice WHERE sku=@sku AND brand=@brand AND marketplace=@marketplace AND warehouse IS NULL;
					DELETE FROM catalog_price_temps WHERE sku=@sku AND brand=@brand AND marketplace=@marketplace AND warehouse IS NULL AND user_id=@user_id AND is_discount=1 AND is_whitelist=0;
				END IF;
			ELSE
				SET @isFirstRecord = (SELECT total_record FROM catalog_price_averages WHERE sku=@sku AND brand=@brand AND marketplace=@marketplace AND warehouse=@warehouse);
				SET @countDataPrice = (SELECT COUNT(*) AS `count` FROM catalog_prices WHERE sku=@sku AND brand=@brand AND marketplace=@marketplace AND warehouse=@warehouse AND is_discount=1 AND is_whitelist=0);
				IF @isFirstRecord <> @totalDataPrice THEN
					
					SET @totalPrice = (SELECT SUM(discount_price) AS `sum` FROM catalog_prices WHERE sku=@sku AND brand=@brand AND marketplace=@marketplace AND warehouse=@warehouse AND is_discount=1 AND is_whitelist=0);
					SET @countNewAvg = (@totalPrice / @countDataPrice);
	
					UPDATE catalog_price_averages SET average_price=@countNewAvg, total_record=@countDataPrice WHERE sku=@sku AND brand=@brand AND marketplace=@marketplace AND warehouse=@warehouse;
					DELETE FROM catalog_price_temps WHERE sku=@sku AND brand=@brand AND marketplace=@marketplace AND warehouse=@warehouse AND user_id=@user_id AND is_discount=1 AND is_whitelist=0;
				END IF;
			END IF;
			INSERT INTO temp (`sku`, `data1`, `data2`, `data3`, `average`, `text`) VALUES(@sku, @brand, @marketplace, @warehouse, @newTotalRecord, @countNewAvg);
		END IF;
	END IF;
END;
$$

DELIMITER ;